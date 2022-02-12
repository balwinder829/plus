<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if(!empty($result)){
            $response['data'] = $result;
        }

        return response()->json($response, $code);
    }

      /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponsePagination($result, $message)
    {
        return response()->json($result, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
    
}
