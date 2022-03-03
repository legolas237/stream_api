<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\RequestRules\UserRules;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{

    /**
     * Find user by username
     * 100: Okay
     * 101: User exist
     * 150: Validation error
     *
     * @param $userName
     * @return Application|ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function findByTelephone(Request $request)
    {
        $this->validate($request, UserRules::checkTelephone());

        $user = User::findByPhoneNumber($request->{'telephone'});

        if($user !== null) {
            return api_response(101, __('messages.user_exist'));
        }

        return api_response(100, 'Okay');
    }

    /**
     * Find user by email
     * 100: Okay
     * 101: User exist
     *
     * @param $email
     * @return Application|ResponseFactory|Response
     */
    public function findByEmail($email)
    {
        $user = User::findByEmail($email);

        if($user !== null) {
            return api_response(101, __('messages.user_exist'));
        }

        return api_response(100, 'Okay');
    }

    /**
     * Serve user avatar
     *
     * Use the following codes
     * 404: Http not found resource: if profile not found
     * 200 : Http code okay
     *
     * @param int $userId
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function serveAvatar(int $userId, Request $request): BinaryFileResponse
    {
        /** @var $user User */
        $user = User::findById($userId);
        $fileName = $request->{'file'};
        $storageOption = config('osm.storage_option');

        if ($user !== null && filled($fileName)) {
            $path = absolute_doc_path($user, $fileName, 'user-');
            if (Storage::disk($storageOption)->exists($path)) {
                $path = storage_path('app/' . $path);
                if ($path !== null) {
                    return response()->file($path);
                }
            }
        }

        return abort(404);
    }

    /**
     * Upload avatar
     * 100: Okay
     * 101: Error
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function uploadAvatar(Request $request)
    {
        $this->validate($request, UserRules::uploadAvatarRules());

        /** @var User $user */
        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            $user = $user->uploadAvatar($request->file('avatar'));

            return api_response(100, 'Ok', $user);
        }

        return api_response(101, __('messages.error'));
    }

}
