<?php

namespace App\Services\Platform;

use App\Models\PhoneCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait PhoneCodeService
{

    /**
     * Add new record
     *
     * @param array $data
     * @return Builder|Model
     */
    public static function store(array $data)
    {
        $data['checked_at'] = null;

        return PhoneCode::query()->create($data);
    }

    /**
     * Update phone code
     *
     * @param array $data
     * @return mixed
     */
    public function updateService(array $data)
    {
        return tap($this)->update($data);
    }

    /**
     * Given a telephone, find the corresponding phone code
     *
     * @param string $telephone
     * @return Builder|Model|object|null
     */
    public static function findByPhoneNumber(string $telephone)
    {
        return PhoneCode::query()->where('telephone', $telephone)->first();
    }

    /**
     * Verify code
     *
     * @param string $code
     * @return bool
     */
    public function verify(string $code): bool
    {
        return $this->{'code'} === $code;
    }

    /**
     * Mark as verified
     *
     * @return mixed
     */
    public function markAsVerified()
    {
        return tap($this)->update(['checked_at' => now()]);
    }

    /**
     * Telephone is verified
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->{'checked_at'} !== null;
    }

    /**
     * Check if the code is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return now() > $this->{'validity'};
    }

    /**
     * Send code for phone number verification
     *
     * @param string $telephone
     * @param PhoneCode|null $phoneCode
     * @return Builder|Model|mixed
     */
    static public function sendOtpCode(string $telephone, PhoneCode $phoneCode = null)
    {
        if ($phoneCode !== null) {
            $phoneCode = $phoneCode->updateService([
                'last_generation' => now(),
                'checked_at' => null,
                'validity' => now()->addDays(config('osm.constants.otp_validity_day')),
                'code' => otp_code(),
            ]);
        } else {
            $phoneCode = PhoneCode::store([
                'last_generation' => now(),
                'telephone' => $telephone,
                'validity' => now()->addDays(config('osm.constants.otp_validity_day')),
                'code' => otp_code(),
            ]);
        }

        return $phoneCode;
    }

}
