<?php

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
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

if (!function_exists('unique_image_file_name')) {
    /**
     * Generate unique file name
     *
     * @param string $prefix
     * @return string
     */
    function unique_image_file_name(string $prefix): string
    {
        return uniqid(strtolower($prefix) . '_')
            . '_' . now()->timestamp
            . config('osm.formats.default_image_file_extension');
    }
}

if (!function_exists('build_user_avatar_url')) {
    /**
     * Build user avatar url
     *
     * @param User $user
     * @return string
     */
    function build_user_avatar_url(User $user)
    {
        return route(
            'serve.user.avatar',
            ['userId' => $user->{'id'}, 'file' => $user->{'avatar'}]
        );
    }
}

if (!function_exists('absolute_doc_path')) {
    /**
     * Builds the path to documents
     *
     * @param $modelInstance
     * @param string $docName
     * @param string $prefix
     * @return string|null
     */
    function absolute_doc_path($modelInstance, string $docName, string $prefix): ?string
    {
        if (empty($docName)) {
            return null;
        }

        if ($modelInstance === null) {
            return null;
        }

        $docPath = sprintf('%s/%s', $prefix . $modelInstance->{'id'}, $docName);
        $fullPath = sprintf(config('osm.paths.docs'), $docPath);

        return $fullPath;
    }
}

if (!function_exists('store_document')) {
    /**
     * Save single document on the server
     *
     * @param string $basePath
     * @param string $type
     * @param UploadedFile $uploadFile
     * @return string|null
     */
    function store_document(string $basePath, string $type, UploadedFile $uploadFile): ?string
    {
        $storageOption = config('osm.storage_option');
        $fileName = unique_image_file_name($type);

        try {
            $uploadFile->storeAs($basePath, $fileName, $storageOption);
        } catch (\Exception $exception) {
            debug_log($exception, 'Upload::File ' . $exception->getMessage());
            $fileName = null;
        }

        return $fileName;
    }
}
