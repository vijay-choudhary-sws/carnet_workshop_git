<?php

namespace App\Http\Controllers;

use App\PurchaseApp;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DomainController extends Controller
{
    // For check domain start
    public function domain()
    {
        return view("Installer.update_domain");
    }

    public function update_domain(Request $request)
    {
        $domain_name = $request->domain_name;
        $licence_key = $request->purchase_key;
        $purchase_email = $request->purchase_email;
        $api_server = 'license.dasinfomedia.com';

        // $whitelist = [
        //     '192.168.1.62',
        // ];
        // if (!in_array($domain_name, $whitelist)) {

        $fp = @fsockopen($api_server, 80, $errno, $errstr, 2);
        if (!$fp) {
            $server_rerror = 'Down';
        } else {
            $server_rerror = "up";
        }

        if ($server_rerror == "up") {
            $url = 'https://license.dasinfomedia.com/admin/api/license/register';
            $fields = array(
                'pkey' => $licence_key,
                'email' => $purchase_email,
                'domain' => $domain_name
            );

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $fields
            ));

            $response = curl_exec($ch);

            curl_close($ch);

            // Parse the JSON response
            $response_data = json_decode($response, true);
            $result = $response_data['message'];

            if ($result == 'Invalid license') {
                return redirect('/domain')->with('message', '1');
                die;
            } elseif ($result == 'License already registered') {
                return redirect('/domain')->with('message', '2');
                die;
            } elseif ($result == 'Please enter a valid URL') {
                return redirect('/domain')->with('message', '3');
                die;
            } elseif ($result == 'Failed to register license') {
                return redirect('/domain')->with('message', '4');
                die;
            }
        } else {
            return redirect('/domain')->with('message', '5');
            die;
        }
        // }

        $setting = Setting::first();
        $setting->domain_name = $domain_name;
        $setting->purchase_email = $purchase_email;
        $setting->licence_key = $licence_key;
        $setting->save();

        return redirect('/');
    }
    // For check domain end

    // For app licence start
    public function store_license(Request $request)
    {
        $app_email = $request->input('email');
        $app_url = $request->input('url');
        $app_licence_key = $request->input('licence_key');

        $purchaseApp = new PurchaseApp();
        $purchaseApp->app_email = $app_email;
        $purchaseApp->app_url = $app_url;
        $purchaseApp->app_licence_key = $app_licence_key;
        $purchaseApp->save();

        $response = [
            'status' => true,
            'code' => 200,
            'message' => 'License Added Successfully',
        ];

        return response()->json($response, 200);
    }

    public function get_license()
    {
        $setting = PurchaseApp::latest()->first();

        if ($setting) {
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Get Licence Successfully',
                'data' => [
                    'app_email' => $setting->app_email,
                    'app_url' => $setting->app_url,
                    'app_licence_key' => $setting->app_licence_key,
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'code' => 401,
                'message' => 'Record not found',
                'data' => null,
            ];
        }

        return response()->json($response, 200);
    }
    // For app licence start
}
