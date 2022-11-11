<?php

namespace App\Http\Controllers;

use App\Models\Housetype;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!has_permissions('read', 'bedroom')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            return view('house_type.index');
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
        if (!has_permissions('create', 'type')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $request->validate([
                'type' => 'required'
            ]);

            Housetype::create([
                'type' => $request->type
            ]);
            return back()->with('success', 'House Type Successfully Added');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!has_permissions('update', 'type')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $id =  $request->edit_id;

            $Type = Housetype::find($id);
            $Type->type = ($request->edit_type) ? $request->edit_type : '';
            $Type->update();


            return back()->with('success', 'House Type Successfully Update');
        }
    }



    public function typeList()
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



        $sql = Housetype::orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('property_type', 'LIKE', "%$search%");
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        foreach ($res as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['type'] = $row->type;

            if (has_permissions('update', 'type')) {
                $operate = '<a  id="' . $row->id . '"  class="btn icon btn-primary btn-sm rounded-pill"  data-bs-toggle="modal" data-bs-target="#editModal"  onclick="setValue(this.id);" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                // if ($row->status == '0') {
                //     $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-primary btn-sm rounded-pill" onclick="return active(this.id);" title="Enable"><i class="bi bi-eye-fill"></i></a>';
                // } else {
                //     $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-danger btn-sm rounded-pill" onclick="return disable(this.id);" title="Disable"><i class="bi bi-eye-slash-fill"></i></a>';
                // }
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
    public function statusupdate(Request $request)
    {
        Housetype::where('id', $request->id)->update(['status' => $request->status]);
        $response['error'] = false;
        return response()->json($response);
    }
}
