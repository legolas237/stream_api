<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\RequestRules\UserRules;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends AuthController
{

    /**
     * Authenticate user
     *
     * Use the following codes
     * 150: Validation error
     * 101: Unknown user
     * 100: Okay
     *
     * @param Request $request
     * @return ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, UserRules::authenticationRules());

        $username = $request->{'username'};
        $password = $request->{'password'};

        /** @var $user User */
        $user = User::findForAuthentication($username);

        if($user !== null && $user->verifyPassword($password)){
            return $this->respondWithToken($user);
        }

        return api_response(101, __('errors.unknown_user', ['username' => $username]));
    }

}
