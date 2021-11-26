<?php

namespace App\RequestRules;

use App\Rules\PhoneNumberRule;

class PhoneCodeRules
{

    /**
     *  Send otp code rules
     *
     * @return array
     */
    public static function sendCodeRules(): array
    {
        return [
            'country_code' => 'required|string',
            'telephone' => ['required', 'string', new PhoneNumberRule(request()->get('country_code'))],
        ];
    }

    /**
     * Otp verification rules
     *
     * @return array
     */
    public static function otpVerificationRules(): array
    {
        return [
            'telephone' => ['required', 'string', new PhoneNumberRule],
            'otp' => ['required', 'string', 'size:' . config('osm.constants.otp_length')],
        ];
    }

}
