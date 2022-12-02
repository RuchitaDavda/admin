<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\PropertysInquiry;
use Illuminate\Http\Request;

class PropertysInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



        if (!has_permissions('read', 'property_inquiry')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            return view('property_inquiry.index');
        }
    }


    public function getPropertyInquiryList()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }



        $sql = PropertysInquiry::with('customer')->with('property')->orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql =  $sql->where('id', 'LIKE', "%$search%")->orwhereHas('property', function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            })->orwhereHas('customer', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")->orwhere('email', 'LIKE', "%$search%")->orwhere('mobile', 'LIKE', "%$search%");
            });
        }


        if ($_GET['status'] != '' && isset($_GET['status'])) {
            $status = $_GET['status'];
            $sql = $sql->where('status', $status);
        }
        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get();
        //return $res;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($res as $row) {
            $operate = '';
            if ((has_permissions('update', 'property_inquiry'))) {
                $operate .= '<a  id="' . $row->id . '" data-status="' . $row->status . '" class="btn icon btn-primary btn-sm rounded-pill mt-2"  data-bs-toggle="modal" data-bs-target="#editModal"  onclick="setValue(this.id);" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            $operate .=  '<a class="btn icon btn-info btn-sm rounded-pill view-property mt-2"  data-bs-toggle="modal" data-bs-target="#ViewPropertyModal"   title="View Property"><i class="bi bi-building"></i></a>&nbsp;&nbsp;';

            $tempRow['id'] = $row->id;
            $tempRow['title'] = $row->property->title;
            $tempRow['name'] = $row->customer->name;
            $tempRow['email'] = $row->customer->email;
            $tempRow['mobile'] = $row->customer->mobile;
            $tempRow['address'] = $row->customer->address;
            $tempRow['client_address'] = $row->property->client_address;
            $tempRow['price'] = $row->property->price;
            $tempRow['carpet_area'] = $row->property->carpet_area;
            $tempRow['built_up_area'] = $row->property->built_up_area;
            $tempRow['plot_area'] = $row->property->plot_area;
            $tempRow['hecta_area'] = $row->property->hecta_area;
            $tempRow['acre'] = $row->property->acre;
            $tempRow['house_type'] = (isset($row->property->houseType)) ? $row->property->houseType->type : '';
            $tempRow['house_no'] = $row->property->house_no;
            $tempRow['survey_no'] = $row->property->survey_no;
            $tempRow['plot_no'] = $row->property->plot_no;
            $tempRow['property_type'] =  ($row->propery_type == '0') ? 'Sell' : 'Rent';
            $tempRow['furnished'] = ($row->furnished == '0') ? 'Furnished' : (($row->furnished == '1') ? 'Semi-Furnished' : 'Not-Furnished');
            $tempRow['inquiry_created'] = $row->created_at->diffForHumans();
            $tempRow['status'] = ($row->status == '0') ? '<span class="badge bg-primary">Pending</span>' : (($row->status == 1) ? '<span class="badge bg-secondary">Accept</span>' : (($row->status == 2) ? '<span class="badge bg-info">In Progress</span>' : (($row->status == 3) ? '<span class="badge bg-success">Complete</span>' : '<span class="badge bg-danger">Cancle</span>')));
            $tempRow['property_owner'] = ($row->property->customer) ? $row->property->customer->name : '';
            $tempRow['property_mobile'] = ($row->property->customer) ? $row->property->customer->mobile : '';
            $tempRow['location'] = ($row->property->latitude != '' && $row->property->longitude != '') ? '&nbsp;<button class="btn icon btn-secondary btn-sm rounded-pill mt-2 CopyLocation"  data-clipboard-text="https://maps.google.com/?q=' . $row->property->latitude . ',' . $row->property->longitude . '"><i class="bi bi-geo-alt-fill"></i></button>' : '';
            $tempRow['unitType'] = $row->property->unittype->measurement;
            $tempRow['category'] = $row->property->category->category;
            $tempRow['state'] = $row->property->state;
            $tempRow['district'] = $row->property->district;
            $tempRow['taluka'] = $row->property->taluka;
            $tempRow['village'] = $row->property->village;
            $tempRow['latitude'] = $row->property->latitude;
            $tempRow['longitude'] = $row->property->longitude;
            $tempRow['description'] = $row->property->description;
            $tempRow['plot_no'] = $row->property->plot_no;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }


    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'property_inquiry')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            $id = $request->id;
            $status = $request->status;
            $status_text = '';
            $PropertysInquiry = PropertysInquiry::with('customer', function ($q) {
                $q->where('notification', 1);
            })->find($id);
            $old_status = $PropertysInquiry->status;

            $PropertysInquiry->status = $status;
            $PropertysInquiry->update();

            if ($status == '0') {
                $status_text  = 'Pending';
            } else if ($status == '1') {
                $status_text  = 'Accept';
            } else if ($status == '2') {
                $status_text  = 'In Progress';
            } else if ($status == '3') {
                $status_text  = 'Complete';
            } else if ($status == '4') {
                $status_text  = 'Cancle';
            }
            $result = '';
            if ($status != $old_status) {

                if ($PropertysInquiry->customer->fcm_id != '') {
                    //START :: Send Notification To Customer
                    $fcm_ids = array();
                    $fcm_ids[] = $PropertysInquiry->customer->fcm_id;
                    if (!empty($fcm_ids)) {
                        $registrationIDs = array_filter($fcm_ids);
                        $fcmMsg = array(
                            'title' => 'Property Inquiry Updated',
                            'message' => 'Your Property Inquiry Updated To ' . $status_text,
                            'type' => 'property_inquiry',
                            'body' => 'Your Property Inquiry Updated To ' . $status_text,
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                            'id' => $PropertysInquiry->id,
                        );
                        $result = send_push_notification($registrationIDs, $fcmMsg);
                    }
                    //END ::  Send Notification To Customer

                    Notifications::create([
                        'title' => 'Property Inquiry Updated',
                        'message' => 'Your Property Inquiry Updated To ' . $status_text,
                        'image' => '',
                        'type' => '1',
                        'send_type' => '0',
                        'customers_id' => $PropertysInquiry->customer->id,
                        'propertys_id' => $PropertysInquiry->id
                    ]);
                }
            }
            $response['error'] = false;
            $response['data'] = $result;
            return response()->json($response);
        }
    }
}
