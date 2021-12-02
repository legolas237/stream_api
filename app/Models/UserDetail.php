<?php

namespace App\Models;

use App\Services\Platform\UserDetailService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{

    use SoftDeletes;
    use HasFactory;
    use UserDetailService;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'telephone',
        'email',
        'name',
        'data_of_birth',
    ];

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
    protected $dates = ['data_of_birth', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Utilities attributes
     */

    public static function creationAttributes(): array
    {
        return  [
            'telephone',
            'email',
            'name',
            'data_of_birth',
        ];
    }
}
