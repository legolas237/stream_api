<?php

namespace App\Services\Platform;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UserService
{

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

}
