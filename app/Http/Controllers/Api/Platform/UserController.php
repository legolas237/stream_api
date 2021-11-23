<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Find user by username
     * 100: Okay
     * 101: User exist
     *
     * @param $userName
     * @return Application|ResponseFactory|Response
     */
    public function findByUsername($userName)
    {
        $user = User::findByUsername($userName);

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

}
