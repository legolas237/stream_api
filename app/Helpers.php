<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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

if (!function_exists('remove_space')) {
    /**Remove string spaces
     *
     * @param string $value
     * @return array|string|string[]|null
     */
    function remove_space(string $value)
    {
        return preg_replace("/\s+/", "", $value);
    }
}


if (!function_exists('otp_code')) {
    /**
     * Generate otp code
     *
     * @return string
     */
    function otp_code()
    {
        $generator = "0123456789";
        $result = "";
        $length = config('osm.constants.otp_length');

        for ($i = 1; $i <= $length; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        return $result;
    }
}

if (!function_exists('debug_log')) {
    /**
     * Log print
     *
     * @param Exception|null $exception
     * @param string|null $prefix
     */
    function debug_log(Exception $exception = null, string $prefix = null)
    {
        $message = $exception !== null
            ? sprintf(
                'file::%s, line::%s, message:: %s',
                $exception->getFile(),
                $exception->getLine(),
                $exception->getMessage()
            )
            : '';

        if ($prefix !== null) {
            Log::info($prefix . ' ::: ' . $message);
        } else {
            Log::info($message);
        }
    }
}
