<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    /**
     * Respond with token
     *
     * @param User $user
     * @return ResponseFactory|Response
     */
    protected function respondWithToken(User $user)
    {
        // Revoke all tokens...
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken($user->{'device'} !== null ? $user->{'device'}->{'device_name'} :  "Personal Access Token");

        return api_response(100, 'Ok', $token);
    }

    /**
     * Log out (Revoke all tokens).
     *
     * @return ResponseFactory|Response
     */
    protected function logout()
    {
        /** @var User $user */
        $user = auth()->user();

        // Revoke all tokens...
        $user->tokens()->delete();

        return api_response(100, 'Ok');
    }

    /**
     * Get auth user
     *
     * Use the following codes
     * 100: Ok
     *
     * @return ResponseFactory|Response
     */
    protected function user()
    {
        /** @var $user  User*/
        $user = auth()->user();

        return api_response(100, 'Ok',  $user);
    }

}
