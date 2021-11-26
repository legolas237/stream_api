<?php

namespace App\Rules;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumberRule implements Rule
{

    protected $countryCode;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $countryCode = null)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! is_string($value)) {
            return false;
        }

        $result = false;
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumberProto = $phoneUtil->parse(remove_space($value));

            if($phoneNumberProto){
                $result = $this->countryCode !== null
                    ? $phoneUtil->isValidNumberForRegion($phoneNumberProto, strtoupper($this->countryCode))
                    : $phoneUtil->isValidNumber($phoneNumberProto);
            }
        } catch (NumberParseException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('errors.invalid_telephone');
    }
}
