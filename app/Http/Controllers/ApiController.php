<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Favourite;
use App\Models\Housetype;
use App\Models\Notifications;
use App\Models\Package;
use App\Models\parameter;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Str;

class ApiController extends Controller
{
    //* START :: get_system_settings   *//
    public function get_system_settings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if (!$validator->fails()) {
            $type = $request->type;
            $result = '';
            if (isset($type)) {
                if ($type == 'company') {
                    $result =  Setting::select('type', 'data')->WhereIn('type', ['company_name', 'company_website', 'company_email', 'company_address', 'company_tel1', 'company_tel2'])->get();


                    if (!empty($result)) {
                        $response['error'] = false;
                        $response['message'] = "Data Fetch Successfully";
                        $response['data'] = $result;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "No data found!";
                    }
                } else {
                    $result =  system_setting($type);

                    if ($result != '') {
                        $response['error'] = false;
                        $response['message'] = "Data Fetch Successfully";
                        $response['data'] = $result;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "No data found!";
                    }
                }
            }
        } else {
            $response = [
                'error' => true,
                'message' => 'Please fill all data and Submit'
            ];
        }
        return response()->json($response);
    }
    //* END :: Get System Setting   *//


    //* START :: user_signup   *//
    public function user_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'firebase_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $type = $request->type;
            $firebase_id = $request->firebase_id;

            $user = Customer::where('firebase_id', $firebase_id)->where('logintype', $type)->get();
            if ($user->isEmpty()) {
                $saveCustomer = new Customer();
                $saveCustomer->name = isset($request->name) ? $request->name : '';
                $saveCustomer->email = isset($request->email) ? $request->email : '';
                $saveCustomer->mobile = isset($request->mobile) ? $request->mobile : '';
                $saveCustomer->profile = isset($request->profile) ? $request->profile : '';
                $saveCustomer->fcm_id = isset($request->fcm_id) ? $request->fcm_id : '';
                $saveCustomer->logintype = isset($request->type) ? $request->type : '';
                $saveCustomer->address = isset($request->address) ? $request->address : '';
                $saveCustomer->firebase_id = isset($request->firebase_id) ? $request->firebase_id : '';
                $saveCustomer->isActive = '1';


                $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                // image upload


                if ($request->hasFile('profile')) {
                    $profile = $request->file('profile');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    $profile->move($destinationPath, $imageName);
                    $saveCustomer->profile = $imageName;
                } else {
                    $saveCustomer->profile = '';
                }



                $saveCustomer->save();
                $response['error'] = false;
                $response['message'] = 'User Register Successfully';

                $credentials = Customer::find($saveCustomer->id);
                $token = JWTAuth::fromUser($credentials);
                try {
                    if (!$token) {
                        $response['error'] = true;
                        $response['message'] = 'Login credentials are invalid.';
                    } else {
                        $credentials->api_token = $token;
                        $credentials->update();
                    }
                } catch (JWTException $e) {
                    $response['error'] = true;
                    $response['message'] = 'Could not create token.';
                }

                if (filter_var($credentials->profile, FILTER_VALIDATE_URL) === false) {
                    $credentials->profile = ($credentials->profile != '') ? url('public') . config('global.USER_IMG_PATH') . $credentials->profile : '';
                } else {
                    $credentials->profile = $credentials->profile;
                }

                // $user2 = new Customer();
                // $user2 = $user2::find($credentials->id);
                // $user2->api_token = $token;
                // $user2->update();

                $response['token'] = $token;
                $response['data'] = $credentials;
            } else {
                $credentials = Customer::where('firebase_id', $firebase_id)->where('logintype', $type)->first();
                try {
                    $token = JWTAuth::fromUser($credentials);
                    if (!$token) {
                        $response['error'] = true;
                        $response['message'] = 'Login credentials are invalid.';
                    } else {
                        $credentials->api_token = $token;
                        $credentials->update();
                    }
                } catch (JWTException $e) {
                    $response['error'] = true;
                    $response['message'] = 'Could not create token.';
                }

                if (filter_var($credentials->profile, FILTER_VALIDATE_URL) === false) {
                    $credentials->profile = ($credentials->profile != '') ? url('public') . config('global.USER_IMG_PATH') . $credentials->profile : '';
                } else {
                    $credentials->profile = $credentials->profile;
                }
                $response['error'] = false;
                $response['message'] = 'Login Successfully';
                $response['token'] = $token;
                $response['data'] = $credentials;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Please fill all data and Submit';
        }
        return response()->json($response);
    }
    //* END :: user_signup   *//


    //* START :: get_slider   *//
    public function get_slider(Request $request)
    {
        $slider = Slider::select('id', 'image', 'sequence', 'category_id', 'propertys_id')->orderBy('sequence', 'ASC')->get();
        if (!$slider->isEmpty()) {
            foreach ($slider as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.SLIDER_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $slider;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }

    //* END :: get_slider   *//


    //* START :: get_categories   *//
    public function get_categories(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $categories = Category::select('id', 'category', 'image', 'parameter_types')->where('status', '1');

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $categories->where('category', 'LIKE', "%$search%");
        }

        if (isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $categories->where('id', '=', $id);
        }

        $total = $categories->get()->count();
        $result = $categories->orderBy('sequence', 'ASC')->skip($offset)->take($limit)->get();




        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            foreach ($result as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.CATEGORY_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }


                $row->parameter_types = parameterTypesByCategory($row->id);
            }

            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }
    //* END :: get_slider   *//


    //* START :: get_house_type   *//
    public function get_house_type(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $type = Housetype::select('id', 'type');

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $type->where('type', 'LIKE', "%$search%");
        }

        $total = $type->get()->count();
        $result = $type->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();


        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }
    //* END :: get_type   *//


    //* START :: get_unit_of_area   *//
    public function get_unit(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $areameasurement = Unit::select('id', 'measurement');

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $areameasurement->where('measurement', 'LIKE', "%$search%");
        }
        $total = $areameasurement->get()->count();
        $result = $areameasurement->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();


        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }
    //* END :: get_unit   *//


    //* START :: update_profile   *//
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);



        if (!$validator->fails()) {
            $id = $request->userid;

            $customer =  Customer::find($id);

            if (!empty($customer)) {
                if (isset($request->name)) {
                    $customer->name = ($request->name) ? $request->name : '';
                }
                if (isset($request->email)  || $request->email == null) {
                    $customer->email = ($request->email != null) ? $request->email : '';
                }
                if (isset($request->mobile)) {
                    $customer->mobile = ($request->mobile) ? $request->mobile : '';
                }

                if (isset($request->fcm_id)) {
                    $customer->fcm_id = ($request->fcm_id) ? $request->fcm_id : '';
                }
                if (isset($request->address)) {
                    $customer->address = ($request->address) ? $request->address : '';
                }
                if (isset($request->address)) {
                    $customer->address = ($request->address) ? $request->address : '';
                }

                if (isset($request->firebase_id)) {
                    $customer->firebase_id = ($request->firebase_id) ? $request->firebase_id : '';
                }


                $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                // image upload


                if ($request->hasFile('profile')) {
                    $old_image = $customer->profile;

                    $profile = $request->file('profile');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    if ($profile->move($destinationPath, $imageName)) {
                        $customer->profile = $imageName;
                        if ($old_image != '') {
                            if (file_exists(public_path('images') . config('global.USER_IMG_PATH') . $old_image)) {
                                unlink(public_path('images') . config('global.USER_IMG_PATH') . $old_image);
                            }
                        }
                    }
                }
                $customer->update();

                if (filter_var($customer->profile, FILTER_VALIDATE_URL) === false) {
                    $customer->profile = ($customer->profile != '') ? url('') . config('global.IMG_PATH') . config('global.USER_IMG_PATH') . $customer->profile : '';
                } else {
                    $customer->profile = $customer->profile;
                }
                $response['error'] = false;
                $response['data'] = $customer;
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_profile   *//


    //* START :: get_user_by_id   *//
    public function get_user_by_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;

            $customer =  Customer::find($id);
            if (!empty($customer)) {
                if (filter_var($customer->profile, FILTER_VALIDATE_URL) === false) {
                    $customer->profile = ($customer->profile != '') ? url('') . config('global.IMG_PATH') . config('global.USER_IMG_PATH') . $customer->profile : '';
                } else {
                    $customer->profile = $customer->profile;
                }
                $response['error'] = false;
                $response['data'] = $customer;
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_user_by_id   *//


    //* START :: get_property   *//
    public function get_property(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        DB::enableQueryLog();
        $property = Property::with('category')->with('housetype')->with('unitType')->with('favourite')->with('assignparameter.parameter');



        $property_type = $request->property_type;  //0 : Buy 1:Rent
        $max_price = $request->max_price;
        $min_price = $request->min_price;
        $top_rated = $request->top_rated;
        $unit_type = $request->unit_type;
        $unit_min = $request->unit_min;
        $unit_max = $request->unit_max;
        $userid = $request->userid;
        $posted_since = $request->posted_since;
        $category_id = $request->category_id;
        $id = $request->id;
        $taluka = $request->taluka;
        $village = $request->village;
        $furnished = $request->furnished;
        $parameter_id = $request->parameter_id;
        if (isset($parameter_id)) {
            echo "in";
            $property = $property->whereHas('assignparameter', function ($q) use ($parameter_id) {
                $q->where('parameter_id', $parameter_id);
            });
        }

        if (isset($userid)) {
            $property = $property->where('added_by', '!=', '0')->where('added_by', '=', $userid);
        } else {
            $property = $property->where('status', 1);
        }
        if (isset($max_price) && isset($min_price)) {
            $property = $property->whereBetween('price', [$min_price, $max_price]);
        }
        if (isset($property_type)) {
            $property = $property->where('propery_type', $property_type);
        }
        if (isset($unit_type) && isset($unit_min) && isset($unit_max)) {
            $property = $property->where('unit_type', $unit_type)->whereBetween('carpet_area', [$unit_min, $unit_max]);
        }
        if (isset($posted_since)) {
            // 0: last_week   1: yesterday
            if ($posted_since == 0) {
                $property = $property->whereBetween(
                    'created_at',
                    [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]
                );
            }
            if ($posted_since == 1) {
                $property =  $property->whereDate('created_at', Carbon::yesterday());
            }
        }
        if (isset($unit_type)) {
            $property = $property->where('unit_type', $unit_type);
        }
        if (isset($category_id)) {
            $property = $property->where('category_id', $category_id);
        }
        if (isset($id)) {
            $property = $property->where('id', $id);
        }
        if (isset($taluka)) {
            $property = $property->where('taluka', $taluka);
        }
        if (isset($village)) {
            $property = $property->where('village', $village);
        }
        if (isset($furnished)) {
            $property = $property->where('furnished', $furnished);
        }



        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;

            $property = $property->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")->orwhere('address', 'LIKE', "%$search%")->orwhereHas('category', function ($query1) use ($search) {
                    $query1->where('category', 'LIKE', "%$search%");
                })->orwhereHas('unitType', function ($query2) use ($search) {
                    $query2->where('measurement', 'LIKE', "%$search%");
                });
            });
        }

        $total = $property->get()->count();

        if (isset($top_rated) && $top_rated == 1) {

            $property = $property->orderBy('total_click', 'DESC');
        } else {
            $property = $property->orderBy('id', 'DESC');
        }



        $result = $property->skip($offset)->take($limit)->get();

        $query = DB::getQueryLog();


        $rows = array();
        $tempRow = array();
        //return $result;
        $count = 1;
        if (!$result->isEmpty()) {
            foreach ($result as $row) {

                $tempRow['id'] = $row->id;
                $tempRow['title'] = $row->title;
                $tempRow['price'] = $row->price;
                $tempRow['category'] = $row->category;
                $tempRow['carpet_area'] = $row->carpet_area;
                $tempRow['built_up_area'] = $row->built_up_area;
                $tempRow['plot_area'] = $row->plot_area;
                $tempRow['hecta_area'] = $row->hecta_area;
                $tempRow['acre'] = $row->acre;
                $tempRow['house_type'] = $row->housetype;
                $tempRow['furnished'] = $row->furnished;
                $tempRow['unit_type'] = $row->unittype;
                $tempRow['description'] = $row->description;
                $tempRow['address'] = $row->address;
                $tempRow['client_address'] = $row->client_address;
                $tempRow['propery_type'] = ($row->propery_type == '0') ? 'Sell' : 'Rent';
                $tempRow['title_image'] = ($row->title_image != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH')  . $row->title_image : '';
                $tempRow['post_created'] = $row->created_at->diffForHumans();
                $tempRow['gallery'] = $row->gallery;
                $tempRow['total_view'] = $row->total_click;
                $tempRow['status'] = $row->status;
                $tempRow['state'] = $row->state;
                $tempRow['district'] = $row->district;
                $tempRow['taluka'] = $row->taluka;
                $tempRow['village'] = $row->village;
                $tempRow['added_by'] = $row->added_by;

                $interested_users = array();
                $s = '';
                foreach ($row->favourite as $interested_user) {

                    if ($interested_user->property_id == $row->id) {

                        array_push($interested_users, $interested_user->user_id);
                        $s .= $interested_user->user_id . ',';
                    }
                }

                $tempRow['interested_user'] = $interested_users;
                $tempRow['total_interested_users'] = count($interested_users);

                $arr = [];

                foreach ($row->assignparameter as $res) {
                    $arr = $arr + [$res->parameter->name => $res->value];
                }
                $tempRow['parameters'] = $arr;
                $rows[] = $tempRow;
                $count++;
            }

            // $response['query'] = $query;
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $rows;
        } else {
            $response['query'] = $query;
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }
    //* END :: get_property   *//



    //* START :: post_property   *//
    public function post_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'category_id' => 'required'
        ]);

        if (!$validator->fails()) {
            $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $Saveproperty = new Property();
            $Saveproperty->category_id = $request->category_id;

            $Saveproperty->title = $request->title;
            $Saveproperty->description = $request->description;
            $Saveproperty->address = $request->address;
            $Saveproperty->client_address = (isset($request->client_address)) ? $request->client_address : '';

            $Saveproperty->propery_type = $request->property_type;
            $Saveproperty->price = $request->price;
            $Saveproperty->unit_type = $request->unit_type;

            $Saveproperty->carpet_area = json_encode($request->parameters);

            $Saveproperty->built_up_area = (isset($request->built_up_area)) ? $request->built_up_area : '';

            $Saveproperty->plot_area = (isset($request->plot_area)) ? $request->plot_area : '';
            $Saveproperty->hecta_area = (isset($request->hecta_area)) ? $request->hecta_area : '';

            $Saveproperty->acre = (isset($request->acre)) ? $request->acre : '';
            $Saveproperty->house_type = (isset($request->house_type)) ? $request->house_type : '';
            $Saveproperty->furnished = (isset($request->furnished)) ? $request->furnished : '';


            $Saveproperty->house_no = (isset($request->house_no)) ? $request->house_no : '';
            $Saveproperty->survey_no = (isset($request->survey_no)) ? $request->survey_no : '';
            $Saveproperty->plot_no = (isset($request->plot_no)) ? $request->plot_no : '';

            $Saveproperty->state = 'Gujarat';
            $Saveproperty->taluka = (isset($request->taluka)) ? $request->taluka : '';
            $Saveproperty->village = (isset($request->village)) ? $request->village : '';
            $Saveproperty->added_by = $request->userid;
            $Saveproperty->status = (isset($request->status)) ? $request->status : 0;


            if ($request->hasFile('title_image')) {
                $profile = $request->file('title_image');
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);
                $Saveproperty->title_image = $imageName;
            } else {
                $Saveproperty->title_image  = '';
            }

            print_r(json_encode($request->parameters));
            $Saveproperty->save();


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


            $response['error'] = false;
            $response['message'] = 'Property Post Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: post_property   *//



    //* START :: update_post_property   *//
    /// This api use for update and delete  property
    public function update_post_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action_type' => 'required'
        ]);

        if (!$validator->fails()) {
            $id = $request->id;
            $action_type = $request->action_type;
            $property = Property::find($id);

            // 0: Update 1: Delete
            if ($action_type == 0) {
                $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                if (isset($request->category_id)) {
                    $property->category_id = $request->category_id;
                }

                if (isset($request->title)) {
                    $property->title = $request->title;
                }

                if (isset($request->description)) {
                    $property->description = $request->description;
                }

                if (isset($request->address)) {
                    $property->address = $request->address;
                }

                if (isset($request->client_address)) {
                    $property->client_address = $request->client_address;
                }

                if (isset($request->propery_type)) {
                    $property->propery_type = $request->propery_type;
                }

                if (isset($request->price)) {
                    $property->price = $request->price;
                }
                if (isset($request->unit_type)) {
                    $property->unit_type = $request->unit_type;
                }

                if (isset($request->parameters)) {
                    $property->carpet_area = json_encode($request->parameters);
                }

                if (isset($request->built_up_area)) {
                    $property->built_up_area = $request->built_up_area;
                }

                if (isset($request->plot_area)) {
                    $property->plot_area = $request->plot_area;
                }

                if (isset($request->hecta_area)) {
                    $property->hecta_area = $request->hecta_area;
                }

                if (isset($request->acre)) {
                    $property->acre = $request->acre;
                }

                if (isset($request->house_type)) {
                    $property->house_type = $request->house_type;
                }

                if (isset($request->furnished)) {
                    $property->furnished = $request->furnished;
                }


                if (isset($request->house_no)) {
                    $property->house_no = $request->house_no;
                }

                if (isset($request->survey_no)) {
                    $property->survey_no = $request->survey_no;
                }

                if (isset($request->plot_no)) {
                    $property->plot_no = $request->plot_no;
                }


                if (isset($request->taluka)) {
                    $property->taluka = $request->taluka;
                }
                if (isset($request->village)) {
                    $property->village = $request->village;
                }
                if (isset($request->status)) {
                    $property->status = $request->status;
                }


                if ($request->hasFile('title_image')) {
                    $profile = $request->file('title_image');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    $profile->move($destinationPath, $imageName);

                    if ($property->title_image != '') {
                        if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') .  $property->title_image)) {
                            unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                        }
                    }
                    $property->title_image = $imageName;
                }


                $property->update();


                /// START :: UPLOAD GALLERY IMAGE

                $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
                if (!is_dir($FolderPath)) {
                    mkdir($FolderPath, 0777, true);
                }


                $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $property->id;
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                if ($request->hasfile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $file) {
                        $name = time() . rand(1, 100) . '.' . $file->extension();
                        $file->move($destinationPath, $name);

                        PropertyImages::create([
                            'image' => $name,
                            'propertys_id' => $property->id
                        ]);
                    }
                }

                /// END :: UPLOAD GALLERY IMAGE


                $response['error'] = false;
                $response['message'] = 'Property Update Succssfully';
            } elseif ($action_type == 1) {
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


                    $slider = Slider::where('propertys_id', $id)->get();

                    foreach ($slider as $row) {
                        $image = $row->image;

                        if (Slider::where('id', $row->id)->delete()) {
                            if (file_exists(public_path('images') . config('global.SLIDER_IMG_PATH') . $image)) {
                                unlink(public_path('images') . config('global.SLIDER_IMG_PATH') . $image);
                            }
                        }
                    }

                    $response['error'] = false;
                    $response['message'] =  'Delete Successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'something wrong';
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_post_property   *//


    //* START :: remove_post_images   *//
    public function remove_post_images(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if (!$validator->fails()) {
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

            $response['error'] = false;
            $response['message'] = 'Property Post Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: remove_post_images   *//



    //* START :: set_property_inquiry   *//
    public function set_property_inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'required',

        ]);

        if (!$validator->fails()) {
            $action_type = $request->action_type;  ////0: add   1:update


            if ($action_type == 0) {
                //add inquiry
                $validator = Validator::make($request->all(), [
                    'property_id' => 'required',
                    'customer_id' => 'required'
                ]);

                if (!$validator->fails()) {
                    $PropertysInquiry = PropertysInquiry::where('propertys_id', $request->property_id)->where('customers_id', $request->customer_id)->first();
                    if (empty($PropertysInquiry)) {
                        PropertysInquiry::create([
                            'propertys_id' => $request->property_id,
                            'customers_id' => $request->customer_id,
                            'status'  => '0'
                        ]);
                        $response['error'] = false;
                        $response['message'] = 'Inquiry Send Succssfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Request Already Submitted';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            } elseif ($action_type == 1) {
                //update inquiry
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                ]);

                if (!$validator->fails()) {
                    $id = $request->id;
                    $propertyInquiry = PropertysInquiry::find($id);


                    $propertyInquiry->status = $request->status;
                    $propertyInquiry->update();


                    $response['error'] = false;
                    $response['message'] = 'Inquiry Update Succssfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }



        return response()->json($response);
    }
    //* END :: set_property_inquiry   *//




    //* START :: get_notification_list   *//
    public function get_notification_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;

            $Notifications =  Notifications::whereRaw("FIND_IN_SET($id,customers_id)")->orwhere('send_type', '1')->orderBy('id', 'DESC')->get();


            if (!$Notifications->isEmpty()) {
                for ($i = 0; $i < count($Notifications); $i++) {
                    $Notifications[$i]->created = $Notifications[$i]->created_at->diffForHumans();
                    $Notifications[$i]->image  = ($Notifications[$i]->image != '') ? url('') . config('global.IMG_PATH') . config('global.NOTIFICATION_IMG_PATH') . $Notifications[$i]->image : '';
                }
                $response['error'] = false;
                $response['data'] = $Notifications;
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_notification_list   *//



    //* START :: get_property_inquiry   *//
    public function get_property_inquiry(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $offset = isset($request->offset) ? $request->offset : 0;
            $limit = isset($request->limit) ? $request->limit : 10;
            $id = $request->customer_id;


            $propertyInquiry = PropertysInquiry::with('property')->where('customers_id', $id);

            $total = $propertyInquiry->get()->count();
            $result = $propertyInquiry->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();



            if (!$result->isEmpty()) {

                foreach ($result as $row) {
                    $row->property->category->parameter_types = parameterTypesByCategory($row->property->category->id);


                    $row->property->propery_type = ($row->property->propery_type == '0') ? 'Sell' : 'Rent';
                    $row->property->title_image = ($row->property->title_image != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH')  . $row->property->title_image : '';
                    $row->property->post_created = $row->property->created_at->diffForHumans();

                    $row->property->house_type = $row->property->housetype;

                    unset($row->property->housetype);
                }

                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";
                $response['total'] = $total;
                $response['data'] = $result;
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_property_inquiry   *//



    //* START :: set_property_total_click   *//
    public function set_property_total_click(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $property_id = $request->property_id;


            $Property = Property::find($property_id);
            $Property->increment('total_click');

            $response['error'] = false;
            $response['message'] = 'Update Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: set_property_total_click   *//


    //* START :: delete_user   *//
    public function delete_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',

        ]);

        if (!$validator->fails()) {
            $userid = $request->userid;

            Customer::find($userid)->delete();
            Property::where('added_by', $userid)->delete();
            PropertysInquiry::where('customers_id', $userid)->delete();

            $response['error'] = false;
            $response['message'] = 'Delete Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: delete_user   *//
    public function bearerToken($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
    //*START :: add favoutite *//
    public function add_favourite(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        $validator = Validator::make($request->all(), [

            'property_id' => 'required',

        ]);
        if (!$validator->fails()) {
            $fav_prop = Favourite::where('user_id', $request->user_id)->where('property_id', $request->property_id)->get();
            if (count($fav_prop) > 1) {
                $response['error'] = false;

                $response['message'] = "Property already add to favourite";
                return response()->json($response);
            }
            $favourite = new Favourite();
            $favourite->user_id = $current_user;
            $favourite->property_id = $request->property_id;
            $favourite->save();
            $response['error'] = false;
            $response['message'] = "Property add to Favourite add successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    //*END :: add favoutite *//
    //*START :: delete favoutite *//
    public function delete_favourite(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        $validator = Validator::make($request->all(), [

            'property_id' => 'required',

        ]);
        if (!$validator->fails()) {
            Favourite::where('property_id', $request->property_id)->where('user_id', $current_user)->delete();

            $response['error'] = false;
            $response['message'] = "Property remove from Favourite add successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_articles(Request $request)
    {

        $article = Article::select('id', 'image', 'title', 'description')->orderBy('id', 'ASC')->paginate();
        if (!$article->isEmpty()) {
            foreach ($article as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.article_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $article;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        return response()->json($response);
    }
    public function store_advertisement(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'advertisment_type' => 'required',
            'user_contact' => 'required',
            'user_email' => 'required',
            'user_id' => 'required',
            'approved_by' => 'required',
            'transaction_id' => 'required',
            'amount_type' => 'required'
        ]);
        if (!$validator->fails()) {
            $adv = new Advertisement();
            $adv->title = $request->title;
            $adv->description = $request->description;
            $adv->start_date = $request->start_date;
            $adv->end_date = $request->end_date;
            $adv->user_contact = $request->user_contact;
            $adv->user_email = $request->user_email;
            $adv->user_id = $request->user_id;
            $adv->approved_by = $request->approved_by;
            $adv->transaction_id = $request->transaction_id;
            $adv->advertisment_type = $request->advertisment_type;
            $adv->amount_type = $request->amount_type;
            $destinationPath = public_path('images') . config('global.ADVERTISEMENT_IMAGE_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if ($request->file('image')) {

                $adv_image = $request->file('image');
                $imageName = microtime(true) . "." . $adv_image->getClientOriginalExtension();
                $adv_image->move($destinationPath, $imageName);
                $adv->image = $imageName;
            }
            $adv->save();
            $response['error'] = false;
            $response['message'] = "Advertisement add successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_advertisement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',

        ]);

        if (!$validator->fails()) {
            $date = date('Y-m-d');
            DB::enableQueryLog();
            $adv = Advertisement::select('id', 'title', 'image', 'description', 'advertisment_type', 'customer_id')->whereBetween('start_date', [$request->start_date, $request->end_date])->whereBetween('end_date', [$request->start_date, $request->end_date])->with('customer:id,name')->orderBy('id', 'ASC')->get();
            // dd(DB::getQueryLog());
            if (!$adv->isEmpty()) {
                foreach ($adv as $row) {
                    if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                        $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.ADVERTISEMENT_IMAGE_PATH') . $row->image : '';
                    } else {
                        $row->image = $row->image;
                    }
                }
                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";
                $response['data'] = $adv;
            } else {
                $response['error'] = true;
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_package(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'start_date' => 'required',
        //     'end_date' => 'required',

        // ]);

        // if (!$validator->fails()) {
        $date = date('Y-m-d');
        DB::enableQueryLog();
        $package = Package::orderBy('id', 'ASC')->get();
        // dd(DB::getQueryLog());
        if (!$package->isEmpty()) {

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $package;
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
        // else {
        //     $response['error'] = true;
        //     $response['message'] = "Please fill all data and Submit";
        // }
        return response()->json($response);
    }
}
