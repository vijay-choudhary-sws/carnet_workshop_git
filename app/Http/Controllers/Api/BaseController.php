<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\PushNotification;

use App\Http\Controllers\Controller as Controller;
class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $message, $errorMessages = null, $code = 200, $status = true)
    {
     $response = [
            'success' => $status,
            'message' => $message,
            'data'    => $result,
            'errors' => $errorMessages,
        ];
        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($error, $errorMessages = [], $code = 404)
    {
     $response = [
            'success' => false,
            'data'    => $errorMessages,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
    
}