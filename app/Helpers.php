<?php

if (!function_exists('api_response')) {
    function api_response(int $code, string $message = null, $data = null)
    {
        return response([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}
