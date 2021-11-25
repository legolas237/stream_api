<?php

namespace App\Http\Middleware;

use Closure;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class ReformatPhoneNumber
{
    /**
     *
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = $request->get('username');
        $telephone = $request->get('telephone');
        $phoneNumber = $request->get('phone_number');

        if(! empty($username)) $this->applyVerification($request, 'username', $username);
        if(! empty($telephone)) $this->applyVerification($request, 'telephone', $telephone);
        if(! empty($phoneNumber)) $this->applyVerification($request, 'phone_number', $phoneNumber);

        return $next($request);
    }

    /**
     * Reformat phone number
     *
     * @param \Illuminate\Http\Request $request
     * @param string $key
     * @param $value
     */
    public function applyVerification($request, string $key, $value)
    {
        if (is_string($value)) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $phoneNumberProto = $phoneUtil->parse($value);

                if($phoneNumberProto){
                    $request->offsetSet(
                        $key,
                        sprintf(
                            "+%s%s",
                            $phoneNumberProto->getCountryCode(),
                            $phoneNumberProto->getNationalNumber()
                        )
                    );
                }
            } catch (NumberParseException $e) {
            }
        }
    }
}
