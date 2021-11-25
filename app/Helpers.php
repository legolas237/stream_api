<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

if (!function_exists('api_response')) {
    /**
     * Generic response format
     *
     * @param int $code
     * @param string|null $message
     * @param null $data
     * @return Application|ResponseFactory|Response
     */
    function api_response(int $code, string $message = null, $data = null)
    {
        return response([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}
