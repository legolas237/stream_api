<?php

namespace App\RequestRules;

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
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

}
