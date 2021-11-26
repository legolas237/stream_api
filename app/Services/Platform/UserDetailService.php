<?php

namespace App\Services\Platform;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Model;

trait UserDetailService
{

    /***
     * Add new record
     *
     * @param array $data
     * @return Model
     */
    public static function store(array $data)
    {
        return UserDetail::query()->create($data);
    }

}
