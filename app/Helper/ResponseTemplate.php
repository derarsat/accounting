<?php
namespace App\Helper;

trait ResponseTemplate
{
    public function sendResponse($data, $success, $errors = null): \Illuminate\Http\JsonResponse
    {
        $res = ["data" => $data, "success" => $success];
        if (isset($errors)) {
            $res["errors"] = $errors;
        }
        return response()->json($res, 200);
    }

}
