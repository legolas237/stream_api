<?php

namespace App\Models;

use App\Models\Sanctum\PersonalAccessToken;
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['profile_picture'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::retrieved(function (User $user) {
            $user->load(['userDetail']);
        });
    }

    public function getProfilePictureAttribute()
    {
        if(filled($this->{'avatar'})){
            return build_user_avatar_url($this);
        }

        return null;
    }

    /**
     * Relations functions
     */

    public function tokens(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

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
