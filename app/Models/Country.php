<?php

namespace App\Models;

use App\Services\Platform\CountryService;
use Giggsey\Locale\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    use CountryService;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['designation', 'country_code', 'alpha_code', 'activated_at'];

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
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'activated_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::retrieved(function (Country $country) {
            $localeDesignation = Locale::getDisplayRegion(
                sprintf('-%s', strtoupper($country->{'alpha_code'})),
                app()->getLocale()
            );

            $country->{'designation'} = $country->getOriginal('designation');
            $country->{'designation'} = $localeDesignation;
        });
    }
}
