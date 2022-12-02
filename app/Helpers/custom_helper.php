<?php

use App\Models\parameter;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('system_setting')) {
    function system_setting($type)
    {
        $db = Setting::where('type', $type)->first();
        return (isset($db)) ? $db->data : '';
    }
}



if (!function_exists('send_push_notification')) {
    //send Notification
    function send_push_notification($registrationIDs = array(), $fcmMsg  = '')
    {

        $get_fcm_key = DB::table('settings')->select('data')->where('type', 'fcm_key')->first();

        $fcm_key = $get_fcm_key->data;

        $registrationIDs_chunks = array_chunk($registrationIDs, 1000);
        foreach ($registrationIDs_chunks as $registrationIDs) {
            $fcmFields = array(
                // 'to' => $singleID,
                'registration_ids' => $registrationIDs, // expects an array of ids
                'priority' => 'high',
                'notification' => $fcmMsg,
                'data' => $fcmMsg
            );
            //print_r(json_encode($fcmFields));
            $headers = array(
                'Authorization: key=' . $fcm_key,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
            $get_result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($get_result, 1);
            return $result;
        }
    }
}


if (!function_exists('get_taluka_from_json')) {
    function get_taluka_from_json()
    {
        $taluka =  json_decode(file_get_contents(public_path('json') . "/kachchh.json"), true);

        $tempRow = array();
        foreach ($taluka['districts'] as $row) {
            $tempRow[] = $row['subDistrict'];
        }
        return $tempRow;
    }
}


if (!function_exists('get_village_from_json')) {
    function get_village_from_json($taluka)
    {


        $village =  json_decode(file_get_contents(public_path('json') . "/kachchh.json"), true);

        $tempRow = array();
        foreach ($village['districts'] as $row) {

            if ($row['subDistrict'] == $taluka) {
                $tempRow[] = $row['villages'];
            }
        }
        return $tempRow;
    }
}



if (!function_exists('parameterTypesByCategory')) {
    function parameterTypesByCategory($category_id)
    {


        $parameter_types = DB::table('categories')->select('parameter_types')->where('categories.id', $category_id)->first();

        $tempRow = array();

        $parameterTypes = explode(',', $parameter_types->parameter_types);

        foreach ($parameterTypes as $row) {
            $par_name = parameter::find($row);
            $tempRow[$row]  = $par_name->name;
        }
        return  $tempRow;
    }
}
