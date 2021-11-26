<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PhoneCode;
use App\RequestRules\PhoneCodeRules;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PhoneCodeController extends Controller
{

    /**
     * Send code for phone number verification
     *
     * Use the following codes
     * 150: Validation error
     * 100: Okay
     * 102: Error
     * 101: Unsupported country
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function send(Request $request)
    {
        $request->validate(PhoneCodeRules::sendCodeRules());

        $telephone = $request->{'telephone'};
        $countryCode = $request->{'country_code'};

        // Supported country ??
        $country = Country::findByCountryCode($countryCode);
        if ($country === null) {
            return api_response(101, __('errors.unsupported_country'));
        }

        /** @var PhoneCode $phoneCode */
        $phoneCode = PhoneCode::findByPhoneNumber($telephone);

        try{
            PhoneCode::sendOtpCode($telephone, $phoneCode);
        } catch (\Exception $exception) {
            debug_log($exception, 'PhoneCodeController::send');
            return api_response(102, __('errors.error'));
        }

        // TODO TRy to send the sms

        return api_response(100, __('messages.otp_sms_sent'));
    }

    /**
     * Check otp code
     *
     * Use the following codes
     * 150: Validation error
     * 101: Expires code
     * 100: Okay
     * 103: Invalid
     * 102: Error
     * 102: Success with existing user account
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function verify(Request $request)
    {
        $request->validate(PhoneCodeRules::otpVerificationRules());

        /** @var PhoneCode $phoneCode */
        $phoneCode = PhoneCode::findByPhoneNumber($request->{'telephone'});

        if ($phoneCode !== null && $phoneCode->verify($request->{'otp'})) {
            $accountExist = $phoneCode->{'user'} !== null;

            if ($phoneCode->isExpired()) {
                return api_response(101, __('errors.otp_expired'));
            }

            try{
                $phoneCode->markAsVerified();
            } catch (\Exception $exception) {
                debug_log($exception, 'PhoneCodeController::verify');
                return api_response(102, __('messages.error'));
            }

            return api_response($accountExist ? 102 : 100, 'Ok');
        }

        return api_response(103, __('errors.invalid_otp_code'));
    }

}
