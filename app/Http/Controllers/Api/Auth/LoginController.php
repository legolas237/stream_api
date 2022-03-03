<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Device;
use App\Models\PhoneCode;
use App\Models\User;
use App\RequestRules\UserRules;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
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
     * 102: Unable telephone
     * 103: Error
     *
     * @param Request $request
     * @return ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, UserRules::authenticationRules());

        $telephone = $request->{'telephone'};
        $password = $request->{'password'};

        /** @var PhoneCode $phoneCode */
        $phoneCode = PhoneCode::findByPhoneNumber($telephone);
        if($phoneCode === null || ! $phoneCode->isVerified() || $phoneCode->{'user'} === null) {
            return api_response(102, __('errors.unable_telephone'));
        }

        /** @var $user User */
        $user = User::findForAuthentication($telephone);

        if($user !== null && $user->verifyPassword($password)){
            $deviceData = Arr::only($request->all(), Device::creationAttributes());

            try{
                // Handle device
                $device = Device::handleDeviceForLogin($deviceData, $user->{'device'});

                // Update device if necessary
                $user->handleDeviceForLogin($device);
            } catch (\Exception $exception) {
                debug_log($exception, 'LoginController::authenticate');
                return api_response(103, __('messages.error'));
            }

            return $this->respondWithToken($user->refresh());
        }

        return api_response(101, __('errors.unknown_user'));
    }

}
