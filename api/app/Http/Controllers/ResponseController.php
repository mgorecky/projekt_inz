<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    protected function success($data, $code = 200){
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'message' => JsonResponse::$statusTexts[$code] = 'OK',
            'data' => $data,
        ], isset(JsonResponse::$statusTexts[$code]) ? $code : JsonResponse::HTTP_OK);
    }

    protected function badRequest($message, $code = 400){
        return response()->json([
            'status' => 'bad request',
            'code' => $code,
            'message' => $message ? $message : 'bad request',
        ], isset(JsonResponse::$statusTexts[$code]) ? $code : JsonResponse::HTTP_BAD_REQUEST);
    }

    protected function unauthorized($message, $code = 401){
        return response()->json([
            'status' => 'unauthorized',
            '$code' => $code,
            'message' => $message ? $message : 'unauthorized',
        ], isset(JsonResponse::$statusTexts[$code]) ? $code : JsonResponse::HTTP_UNAUTHORIZED);
    }

    protected function forbidden($message, $code = 403){
        return response()->json([
            'status' => 'forbidden',
            '$code' => $code,
            'message' => $message ? $message : 'forbidden',
        ], isset(JsonResponse::$statusTexts[$code]) ? $code : JsonResponse::HTTP_FORBIDDEN);
    }

    protected function notFound($message, $code = 404){
        return response()->json([
            'status' => 'Not Found',
            'status_code' => $code,
            'message' => $message ? $message : 'Not Found',
        ], isset(JsonResponse::$statusTexts[$code]) ? $code : JsonResponse::HTTP_NOT_FOUND);
    }
}
