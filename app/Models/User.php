<?php

namespace App\Models;

use App\Services\Platform\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use UserService;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'password',
        'avatar',
        'email_verified_at',

        'device_id',
        'user_detail_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_id',
        'user_detail_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at'];

    /**
     * Relations functions
     */

    public function userDetail(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserDetail::class);
    }

    public function device(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Others callback
     */

    public function verifyPassword(string $plainTextPassword): bool
    {
        return Hash::check($plainTextPassword, $this->{'password'});
    }

    /**
     * Utilities attributes
     */

    public static function creationAttributes(): array
    {
        return  [
            'password',
        ];
    }

}
