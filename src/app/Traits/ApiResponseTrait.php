<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function sendResponse($status, $data, $responseArr)
    {
        // $statusCode = $responseCodeObject->status;
        // $responseCode = $responseCodeObject->response_code;


        if ($status) {
            return response()->json([
                'ok' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'err' => $responseArr['err'],
                'msg' => $responseArr['msg']
            ], $responseArr['code']);
        }
    }
}
