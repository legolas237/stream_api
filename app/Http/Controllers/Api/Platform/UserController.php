<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\RequestRules\UserRules;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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

}
