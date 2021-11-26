<?php

namespace App\Services\Platform;

use App\Models\Device;
use App\Models\PhoneCode;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait UserService
{

    /***
     * Add new record
     *
     * @param array $data
     * @return Model
     */
    public static function store(array $data)
    {
        return User::query()->create($data);
    }

    /**
     * Find user by phone number
     *
     * @param string $telephone
     * @return Builder|Model|object|null
     */
    public static function findByPhoneNumber(string $telephone)
    {
        return User::query()->whereHas("userDetail", function(Builder $query) use($telephone) {
            $query->where('telephone', $telephone);
        })->first();
    }

    /**
     * Find user by username
     *
     * @param string $userName
     * @return Builder|Model|object|null
     */
    public static function findByUsername(string $userName)
    {
        return User::query()->whereHas("userDetail", function(Builder $query) use($userName) {
            $query->where('username', $userName);
        })->first();
    }

    /**
     * Find user using email
     *
     * @param string $email
     * @return Builder|Model|object|null
     */
    public static function findByEmail(string $email)
    {
        return User::query()->whereHas("userDetail", function(Builder $query) use($email) {
            $query->where('email', $email);
        })->first();
    }

    /**
     * Find user for authentication
     *
     * @param string $username
     * @return Builder|Model|object|null
     */
    public static function findForAuthentication(string $username)
    {
        return User::query()->whereHas("userDetail", function(Builder $query) use($username) {
            $query->where('telephone', $username);
        })->first();
    }

    /**
     * Update user
     *
     * @param array $data
     * @return mixed
     */
    public function updateService(array $data)
    {
        return tap($this)->update($data);
    }

    /**
     * Managing devices during connection
     *
     * @param Device $device
     * @return mixed
     */
    public function handleDeviceForLogin(Device $device)
    {
        return $this->updateService(['device_id' => $device->{'id'}]);
    }

    /**
     * Registers a new user
     *
     * @param PhoneCode $phoneCode
     * @param array $data
     * @return Model|null
     */
    static public function registration(PhoneCode $phoneCode, array $data): ?Model
    {
        $user = null;

        DB::beginTransaction();

        try{
            $deviceData = Arr::only($data, Device::creationAttributes());
            $userDetailData = Arr::only($data, UserDetail::creationAttributes());
            $userData = Arr::only($data, User::creationAttributes());

            $userData['password'] = Hash::make($userData['password']);
            // Update reference
            $userData['user_detail_id'] = (UserDetail::store($userDetailData))->{'id'};
            $userData['device_id'] = (Device::store($deviceData))->{'id'};

            $user = self::store($userData);

            // Update phone code
            $phoneCode->updateService(['user_id' => $user->{'id'}]);

            DB::commit();
        } catch (\Exception $exception) {
            debug_log($exception, 'UserService::registration');
            DB::rollBack();
        }

        return $user;
    }
}
