<?php

if (!function_exists('api_response')) {
    /**
     * Generic response format
     *
     * @param int $code
     * @param string|null $message
     * @param null $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
