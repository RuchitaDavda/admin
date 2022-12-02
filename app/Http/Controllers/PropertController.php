<?php

namespace App\Http\Controllers;

use App\Models\AssignParameters;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Housetype;
use App\Models\Notifications;
use App\Models\parameter;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;

class PropertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!has_permissions('read', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {


            $category = Category::all();



            return view('property.index', compact('category'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!has_permissions('create', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $category = Category::where('status', '1')->get();
            $unittype = Unit::all()->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['measurement']];
            });
            $housetype = Housetype::all();
            $taluka = get_taluka_from_json();
            $parameters = parameter::all();
            return view('property.create', compact('category', 'unittype', 'housetype', 'taluka', 'parameters'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $arr = [];


        if (!has_permissions('read', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $request->validate([
                'gallery_images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'title_image.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $Saveproperty = new Property();

            $Saveproperty->category_id = $request->category;

            $Saveproperty->title = $request->title;
            $Saveproperty->description = $request->description;
            $Saveproperty->address = $request->address;
            $Saveproperty->client_address = $request->client_address;



            $Saveproperty->propery_type = $request->property_type;
            $Saveproperty->price = $request->price;
            $Saveproperty->unit_type = $request->unit_type;
            // $Saveproperty->parameters = (json_encode($arr));

            // $Saveproperty->built_up_area = (isset($request->built_up_area)) ? $request->built_up_area : '';

            // $Saveproperty->plot_area = (isset($request->plot_area)) ? $request->plot_area : '';
            // $Saveproperty->hecta_area = (isset($request->hecta_area)) ? $request->hecta_area : '';

            // $Saveproperty->acre = (isset($request->acre)) ? $request->acre : '';
            // $Saveproperty->house_type = (isset($request->house_type)) ? $request->house_type : '';
            // $Saveproperty->furnished = (isset($request->furnished)) ? $request->furnished : '';


            // $Saveproperty->house_no = (isset($request->house_no)) ? $request->house_no : '';
            // $Saveproperty->survey_no = (isset($request->survey_no)) ? $request->survey_no : '';
            // $Saveproperty->plot_no = (isset($request->plot_no)) ? $request->plot_no : '';


            $Saveproperty->state = (isset($request->state)) ? $request->state : '';
            $Saveproperty->taluka = (isset($request->taluka)) ? $request->taluka : '';
            $Saveproperty->village = (isset($request->village)) ? $request->village : '';


            $Saveproperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
            $Saveproperty->longitude = (isset($request->longitude)) ? $request->longitude : '';

            if ($request->hasFile('title_image')) {
                $profile = $request->file('title_image');
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);
                $Saveproperty->title_image = $imageName;
            } else {
                $Saveproperty->title_image  = '';
            }


            $Saveproperty->save();

            $parameters = parameter::all();
            foreach ($parameters as $par) {
                if ($request->has($par->id)) {
                    echo "in";
                    $assign_parameter = new AssignParameters();
                    $assign_parameter->parameter_id = $par->id;
                    $assign_parameter->value = $request->input($par->id);
                    $assign_parameter->property_id = $Saveproperty->id;
                    $assign_parameter->modal()->associate($Saveproperty);

                    $assign_parameter->save();
                    $arr = $arr + [$par->id => $request->input($par->id)];
                }
            }

            /// START :: UPLOAD GALLERY IMAGE

            $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($FolderPath)) {
                mkdir($FolderPath, 0777, true);
            }


            $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $Saveproperty->id;
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($request->hasfile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move($destinationPath, $name);

                    PropertyImages::create([
                        'image' => $name,
                        'propertys_id' => $Saveproperty->id
                    ]);
                }
            }

            /// END :: UPLOAD GALLERY IMAGE


            return back()->with('success', 'Successfully Added');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!has_permissions('update', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $category = Category::all()->where('status', '1')->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['category']];
            });

            $category = Category::where('status', '1')->get();
            $parameters = parameter::all();
            $unittype = Unit::all()->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['measurement']];
            });


            $list = Property::with('assignparameter.parameter')->where('id', $id)->get()->first();
            // dd($list->toArray());
            $housetype = Housetype::all();
            $taluka = get_taluka_from_json();
            $village = get_village_from_json($list->taluka)[0];

            $arr = json_decode($list->carpet_area);
            $par_arr = [];
            $par_id = [];
            foreach ($list->assignparameter as  $par) {


                $par_arr = $par_arr + [$par->parameter->name => $par->value];
                $par_id = $par_id + [$par->parameter->name => $par->value];
            }



            return view('property.edit', compact('category', 'unittype', 'housetype', 'village', 'taluka', 'list', 'id', 'par_arr', 'parameters', 'par_id'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!has_permissions('update', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {

            // dd($request->toArray());
            $arr = [];

            $UpdateProperty = Property::with('assignparameter.parameter')->find($id);
            $parameters = parameter::all();
            foreach ($UpdateProperty->assignparameter as $par) {
                echo ($par->parameter->name);
                $update_parameter = AssignParameters::where('parameter_id', $par->parameter->id)->where('modal_id', $id)->first();
                $update_parameter->value = $request->input($par->id);
                $update_parameter->save();
            }

            $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }


            $UpdateProperty->category_id = $request->category;
            $UpdateProperty->title = $request->title;
            $UpdateProperty->description = $request->description;
            $UpdateProperty->address = $request->address;
            $UpdateProperty->client_address = $request->client_address;
            $UpdateProperty->propery_type = $request->property_type;
            $UpdateProperty->price = $request->price;
            $UpdateProperty->unit_type = $request->unit_type;

            $UpdateProperty->propery_type = $request->property_type;
            $UpdateProperty->price = $request->price;
            $UpdateProperty->unit_type = $request->unit_type;


            // $UpdateProperty->parameters = (json_encode($arr));
            $UpdateProperty->built_up_area = (isset($request->built_up_area)) ? $request->built_up_area : '';
            $UpdateProperty->plot_area = (isset($request->plot_area)) ? $request->plot_area : '';
            $UpdateProperty->hecta_area = (isset($request->hecta_area)) ? $request->hecta_area : '';

            $UpdateProperty->acre = (isset($request->acre)) ? $request->acre : '';
            $UpdateProperty->house_type = (isset($request->house_type)) ? $request->house_type : '';
            $UpdateProperty->furnished = (isset($request->furnished)) ? $request->furnished : '';

            $UpdateProperty->house_no = (isset($request->house_no)) ? $request->house_no : '';
            $UpdateProperty->survey_no = (isset($request->survey_no)) ? $request->survey_no : '';
            $UpdateProperty->plot_no = (isset($request->plot_no)) ? $request->plot_no : '';


            $UpdateProperty->state = (isset($request->state)) ? $request->state : '';
            $UpdateProperty->taluka = (isset($request->taluka)) ? $request->taluka : '';
            $UpdateProperty->village = (isset($request->village)) ? $request->village : '';


            $UpdateProperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
            $UpdateProperty->longitude = (isset($request->longitude)) ? $request->longitude : '';


            if ($request->hasFile('title_image')) {
                $profile = $request->file('title_image');
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);

                if ($UpdateProperty->title_image != '') {
                    if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') .  $UpdateProperty->title_image)) {
                        unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $UpdateProperty->title_image);
                    }
                }
                $UpdateProperty->title_image = $imageName;
            }


            $UpdateProperty->update();


            /// START :: UPLOAD GALLERY IMAGE

            $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($FolderPath)) {
                mkdir($FolderPath, 0777, true);
            }


            $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $UpdateProperty->id;
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($request->hasfile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move($destinationPath, $name);

                    PropertyImages::create([
                        'image' => $name,
                        'propertys_id' => $UpdateProperty->id
                    ]);
                }
            }

            /// END :: UPLOAD GALLERY IMAGE

            return back()->with('success', 'Successfully Update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!has_permissions('delete', 'property')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $property = Property::find($id);

            if ($property->delete()) {
                if ($property->title_image != '') {
                    if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image)) {
                        unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                    }
                }
                foreach ($property->gallery as $row) {
                    if (PropertyImages::where('id', $row->id)->delete()) {
                        if ($row->image_url != '') {
                            if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image)) {
                                unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image);
                            }
                        }
                    }
                }
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id);
                Notifications::where('propertys_id', $id)->delete();
                return back()->with('success', 'Property Deleted Successfully');
            } else {
                return back()->with('error', 'Something Wrong');
            }
        }
    }



    public function getPropertyList()
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



        $sql = Property::with('category')->with('houseType')->with('unitType')->with('assignparameter.parameter')->orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql = $sql->where('id', 'LIKE', "%$search%")->orwhere('title', 'LIKE', "%$search%")->orwhere('address', 'LIKE', "%$search%")->orwhereHas('category', function ($query) use ($search) {
                $query->where('category', 'LIKE', "%$search%");
            })->orwhereHas('unitType', function ($query) use ($search) {
                $query->where('measurement', 'LIKE', "%$search%");
            });
        }

        if ($_GET['status'] != '' && isset($_GET['status'])) {
            $status = $_GET['status'];
            $sql = $sql->where('status', $status);
        }

        if ($_GET['customer_id'] != '' && isset($_GET['customer_id'])) {
            $customer_id = $_GET['customer_id'];
            $sql = $sql->where('added_by', $customer_id);
        }

        if ($_GET['category'] != '' && isset($_GET['category'])) {
            $category_id = $_GET['category'];
            $sql = $sql->where('category_id', $category_id);
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


        $operate = '';
        foreach ($res as $row) {



            if (has_permissions('update', 'property')) {
                $operate = '<a  href="' . route('property.edit', $row->id) . '"  class="btn icon btn-primary btn-sm rounded-pill mt-2" title="Edit"><i class="fa fa-edit"></i></a>';

                if ($row->status == '0') {
                    $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-primary btn-sm rounded-pill mt-2" onclick="return active(this.id);" title="Enable"><i class="bi bi-eye-fill"></i></a>';
                } else {
                    $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-danger btn-sm rounded-pill mt-2" onclick="return disable(this.id);" title="Disable"><i class="bi bi-eye-slash-fill"></i></a>';
                }
            }

            if (has_permissions('delete', 'property')) {
                // $operate .= '&nbsp;<a href="' . route('property.destroy', $row->id) . '" onclick="return confirmationDelete(event);" class="btn icon btn-danger btn-sm rounded-pill mt-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" title="Delete"><i class="bi bi-trash"></i></a>';

                $operate .= '&nbsp;<a href="' . route('property.destroy', $row->id) . '" onclick="return confirmationDelete(event);" class="btn icon btn-danger btn-sm rounded-pill mt-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" title="Delete"><i class="bi bi-trash"></i></a>';
            }


            $parameter = "";

            foreach ($row->assignparameter  as $res) {


                $parameter .= $res->parameter->name . ":" . $res->value . "<br>";
            }
            $tempRow['parameters'] = $parameter;

            $tempRow['id'] = $row->id;
            $tempRow['title'] = $row->title;
            $tempRow['built_up_area'] = $row->built_up_area;
            $tempRow['plot_area'] = $row->plot_area;
            $tempRow['hecta_area'] = $row->hecta_area;
            $tempRow['acre'] = $row->acre;

            $tempRow['house_no'] = $row->house_no;
            $tempRow['survey_no'] = $row->survey_no;
            $tempRow['plot_no'] = $row->plot_no;


            $tempRow['category'] = isset($row->category->category) ? $row->category->category : '';
            $tempRow['type'] = (!empty($row->housetype)) ? $row->housetype->type : '';

            $tempRow['unit_type'] = $row->unittype->measurement;
            $tempRow['address'] = $row->address;
            $tempRow['client_address'] = $row->client_address;
            $tempRow['furnished'] = ($row->furnished == '0') ? 'Furnished' : (($row->furnished == '1') ? 'Semi-Furnished' : 'Not-Furnished');
            $tempRow['propery_type'] = ($row->propery_type == '0') ? 'Sell' : 'Rent';
            $tempRow['price'] = $row->price;
            $tempRow['title_image'] = ($row->title_image != '') ? '<a class="image-popup-no-margins" href="' . url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH')  . $row->title_image . '"><img class="rounded avatar-md shadow img-fluid" alt="" src="' . url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $row->title_image . '" width="55"></a>' : '';
            $tempRow['status'] = ($row->status == '0') ? '<span class="badge rounded-pill bg-danger">Inactive</span>' : '<span class="badge rounded-pill bg-success">Active</span>';

            if ($row->added_by != 0) {
                $getPostedBy = Customer::where('id', $row->added_by)->get()->first();
                $tempRow['added_by'] = (!empty($getPostedBy)) ? $getPostedBy->name : '';
                $tempRow['mobile'] = (!empty($getPostedBy)) ? $getPostedBy->mobile : '';
                //$tempRow['address'] = (!empty($getPostedBy)) ? $getPostedBy->address : '';
            } else {
                $tempRow['added_by'] = 'Administrator';
                $tempRow['mobile'] = '';
                // $tempRow['address'] = '';
            }


            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }



    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'property')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Property::where('id', $request->id)->update(['status' => $request->status]);
            $response['error'] = false;
            return response()->json($response);
        }
    }


    public function removeGalleryImage(Request $request)
    {
        if (!has_permissions('delete', 'slider')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $id = $request->id;

            $getImage = PropertyImages::where('id', $id)->first();
            $image = $getImage->image;
            $propertys_id =  $getImage->propertys_id;

            if (PropertyImages::where('id', $id)->delete()) {
                if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image)) {
                    unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image);
                }
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }

            $countImage = PropertyImages::where('propertys_id', $propertys_id)->get();
            if ($countImage->count() == 0) {
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id);
            }
            return response()->json($response);
        }
    }


    public function getVillageByTaluka(Request $request)
    {
        $taluka = $request->taluka;
        if ($taluka != '') {
            $village = get_village_from_json($taluka);

            if (!empty($village)) {
                $response['error'] = false;
                $response['data'] = $village[0];
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }


        return response()->json($response);
    }
}
