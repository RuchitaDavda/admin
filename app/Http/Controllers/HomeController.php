<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        if (!has_permissions('read', 'dashboard')) {
            return redirect('dashboard')->with('error', PERMISSION_ERROR_MSG);
        } else {


            $list['total_property'] = Property::all()->count();
            $list['total_active_property'] = Property::where('status', '1')->get()->count();
            $list['total_inactive_property'] = Property::where('status', '0')->get()->count();


            $list['total_property_inquiry'] = PropertysInquiry::all()->count();
            $list['total_property_inquiry_pending'] = PropertysInquiry::where('status', '0')->get()->count();
            $list['total_property_inquiry_accept'] = PropertysInquiry::where('status', '1')->get()->count();
            $list['total_property_inquiry_in_progress'] = PropertysInquiry::where('status', '2')->get()->count();
            $list['total_property_inquiry_complete'] = PropertysInquiry::where('status', '3')->get()->count();
            $list['total_property_inquiry_cancle'] = PropertysInquiry::where('status', '4')->get()->count();


            $list['total_customer'] = Customer::all()->count();

            return view('home', compact('list'));
        }
    }
    public function blank_dashboard()
    {


        return view('blank_home');
    }


    public function change_password()
    {

        return view('change_password.index');
    }


    public function check_password(Request $request)
    {
        $id = Auth::id();
        $oldpassword = $request->old_password;
        $user = DB::table('users')->where('id', $id)->first();
        return (password_verify($oldpassword, $user->password)) ? true : false;
    }


    public function store_password(Request $request)
    {

        $confPassword = $request->confPassword;
        $id = Auth::id();
        $role = Auth::user()->type;

        $users = User::find($id);
        if ($role == 0) {
            $users->name  = $request->name;
            $users->email  = $request->email;
        }

        if (isset($confPassword) && $confPassword != '') {
            $users->password = Hash::make($confPassword);
        }

        $users->update();
        return back()->with('success', 'Password Change Successfully');
    }


    public function privacy_policy()
    {
        echo system_setting('privacy_policy');
    }
}
