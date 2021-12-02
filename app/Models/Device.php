<?php

namespace App\Models;

use App\Services\Platform\DeviceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;
    use DeviceService;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['device_name', 'device_id', 'ip', 'os'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * Utilities attributes
     */

    public static function creationAttributes(): array
    {
        return  [
            'device_name',
            'device_id',
            'ip',
            'os',
        ];
    }
}
