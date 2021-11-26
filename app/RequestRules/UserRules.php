<?php

namespace App\RequestRules;

use App\Rules\PhoneNumberRule;

class UserRules
{

    /**
     * Authentication rules
     *
     * @return array
     */
    public static function authenticationRules(): array
    {
        return [
            'telephone' => ['required', 'string', new PhoneNumberRule()],
            'password' => 'required|string',

            'device_name' => 'required|string',
            'device_id' => 'string',
            'ip' => 'string',
            'os' => 'required|string',
            'mac' => 'required|string',
        ];
    }

    /**
     * Registration rules
     *
     * @return array
     */
    public static function registrationRules(): array
    {
        return [
            'password' => 'required|string',
            'telephone' => ['required', 'string', new PhoneNumberRule()],
            'username' => 'required|string',
            'email' => 'required|string|email',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'data_of_birth' => ['required', 'string', 'date_format:' . config('osm.formats.date')],
            'device_name' => 'required|string',
            'device_id' => 'string',
            'ip' => 'string',
            'os' => 'required|string',
            'mac' => 'required|string',
        ];
    }

}
