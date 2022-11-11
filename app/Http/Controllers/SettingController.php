<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = last(request()->segments());
        $type1 = str_replace('-', '_', $type);
        $data = Setting::select('data')->where('type', $type1)->pluck('data')->first();
        return view('settings.'.$type, compact('data', 'type'));
    }

    public function settings(Request $request)
    {
        $request->validate([
            'data' => 'required',
        ]);

        $type1 = $request->type;
        if ($type1 != '') {
            $message = Setting::where('type', $type1)->first();
            if (empty($message)) {
                Setting::create([
                    'type' => $type1,
                    'data' => $request->data
                ]);
            } else {
                $data['data'] = $request->data;
                Setting::where('type', $type1)->update($data);
            }
            return redirect(str_replace('_', '-', $type1))->with('success', 'Setting Update');
        } else {
            return redirect(str_replace('_', '-', $type1))->with('error', 'Something Wrong');
        }
    }

    public function system_settings(Request $request)
    {
        $input = $request->all();

        unset($input['btnAdd']);
        unset($input['_token']);
        foreach ($input as $key => $value) {
            $result = Setting::where('type', $key)->first();
            if (empty($result)) {
                Setting::create([
                    'type' => $key,
                    'data' => $value
                ]);
            } else {
                $data['data'] = ($value) ? $value : '';
                Setting::where('type', $key)->update($data);
            }
        }

        return redirect()->back()->with('success', 'Setting Update');
    }
}
