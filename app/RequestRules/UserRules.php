<?php

namespace App\RequestRules;

use App\Rules\PhoneNumberRule;

class UserRules
{

    /**
     * Telephone verification rules
     *
     * @return array
     */
    public static function checkTelephone(): array
    {
        return [
            'telephone' => ['required', 'string', new PhoneNumberRule()],
        ];
    }

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
            'name' => 'required|string',
            'data_of_birth' => ['required', 'string', 'date_format:' . config('osm.formats.date')],
            'device_name' => 'required|string',
            'ip' => 'string',
            'os' => 'required|string',
            'device_id' => 'required|string',
        ];
    }

    /**
     * Upload avatar rules
     *
     * @return array
     */
    public static function uploadAvatarRules(): array
    {
        return [
            'avatar' =>  'required|image|max:2048|mimes:jpg,png,gif,jpeg', // 1 MB
        ];
    }

}
