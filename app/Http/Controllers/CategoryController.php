<?php

namespace App\Http\Controllers;

use App\Models\Category;

use App\Models\Type;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!has_permissions('read', 'categories')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            return view('categories.index');
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
        if (!has_permissions('create', 'categories')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'category' => 'required'
            ]);
            $saveCategories = new Category();
            $destinationPath = public_path('images') . config('global.CATEGORY_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // image upload


            if ($request->hasFile('image')) {
                $profile = $request->file('image');
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);
                $saveCategories->image = $imageName;
            } else {
                $saveCategories->image  = '';
            }

            $saveCategories->category = ($request->category) ? $request->category : '';
            $saveCategories->parameter_types = ($request->parameter_type) ? implode(',',$request->parameter_type) : '';
            $saveCategories->save();
            return back()->with('success', 'Category Successfully Added');
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


        if (!has_permissions('update', 'categories')) {
            return redirect()->back()->with('error', PERMISSION_ERROR_MSG);
        } else {
            $id =  $request->edit_id;
            $old_image =  $request->old_image;
            $Category = Category::find($id);



            $destinationPath = public_path('images') . config('global.CATEGORY_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // image upload


            if ($request->hasFile('image')) {
                $profile = $request->file('image');
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);
                $Category->image = $imageName;

                if (file_exists(public_path('images') . config('global.CATEGORY_IMG_PATH') . $old_image)) {
                    unlink(public_path('images') . config('global.CATEGORY_IMG_PATH') . $old_image);
                }
            } else {
                $Category->image  = $old_image;
            }

            $Category->category = ($request->edit_category) ? $request->edit_category : '';
            $Category->status = ($request->status) ? $request->status : 0;
            $Category->sequence = ($request->sequence) ? $request->sequence : 0;
            $Category->parameter_types = ($request->edit_parameter_type) ? implode(',',$request->edit_parameter_type) : '';

            $Category->update();



             return back()->with('success', 'Category Successfully Update');
        }
    }



    public function categoryList() {
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



        $sql = Category::orderBy($sort,$order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('category', 'LIKE', "%$search%");
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get()->append('ParameterTypeNames');
        //return $res;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        foreach ($res as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['category'] = $row->category;
            $tempRow['status'] = ($row->status == '0') ? '<span class="badge rounded-pill bg-danger">Inactive</span>' : '<span class="badge rounded-pill bg-success">Active</span>';
            $tempRow['image'] = ($row->image != '') ? '<a class="image-popup-no-margins" href="' .url('images') . config('global.CATEGORY_IMG_PATH')  . $row->image . '"><img class="rounded avatar-md shadow img-fluid" alt="" src="' . url('images'). config('global.CATEGORY_IMG_PATH')  . $row->image . '" width="55"></a>' : '';


            $tempRow['sequence'] = $row->sequence;
            $tempRow['type'] = isset($row->ParameterTypeNames) ? $row->ParameterTypeNames : '';

            $ids= isset($row->parameter_types) ? $row->parameter_types : '';
            $operate = '<a  id="' . $row->id . '"  class="btn icon btn-primary btn-sm rounded-pill" data-status="'.$row->status.'" data-oldimage="'.$row->image.'" data-types="'.$ids.'" data-bs-toggle="modal" data-bs-target="#editModal"  onclick="setValue(this.id);" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';

            if($row->status == '0'){
                $operate .=   '&nbsp;<a id="'.$row->id.'" class="btn icon btn-primary btn-sm rounded-pill" onclick="return active(this.id);" title="Enable"><i class="bi bi-eye-fill"></i></a>';
            }else{
                $operate .=   '&nbsp;<a id="'.$row->id.'" class="btn icon btn-danger btn-sm rounded-pill" onclick="return disable(this.id);" title="Disable"><i class="bi bi-eye-slash-fill"></i></a>';
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }



    public function updateCategory(Request $request)
    {
        if (!has_permissions('delete', 'categories')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {

            Category::where('id',$request->id)->update(['status' => $request->status]);
            $response['error'] = false;
            return response()->json($response);
        }
    }
}
