<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\PhoneCode;
use App\Models\User;
use App\RequestRules\UserRules;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RegisterController extends AuthController
{

    /**
     * Registers a new user
     *
     * Use the following codes
     * 150: Validation error
     * 102: Unable to verify telephone
     * 103: Error
     * 100: Okay
     *
     * @param Request $request
     * @return ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function registration(Request $request)
    {
        $this->validate($request, UserRules::registrationRules());

        // Check phone number
        if(User::findByPhoneNumber($request->{'telephone'})){
            return api_response(150, __('errors.validation_error'), array('telephone' => [__('errors.telephone_exist')]));
        }

        /** @var PhoneCode $phoneCode */
        $phoneCode = PhoneCode::findByPhoneNumber($request->{'telephone'});
        if($phoneCode === null || ! $phoneCode->isVerified()) {
            return api_response(102, __('errors.unable_telephone'));
        }

        /** @var User $user */
        $user = User::registration($phoneCode, $request->all());

        if($user !== null) {
            return $this->respondWithToken($user);
        }

        return api_response(103, __('errors.error'));
    }

}
