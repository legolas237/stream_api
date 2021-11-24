<?php

namespace App\Models;

use App\Services\Platform\CountryService;
use Giggsey\Locale\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PragmaRX\Countries\Package\Support\Collection as PragmaRXCollection;
use PragmaRX\Countries\Package\Countries;

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['native_name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'activated_at'];

    /**
     * Determine if the user is an administrator.
     *
     * @return string
     */
    public function getNativeNameAttribute()
    {
        $countries = new Countries();
        $country = $countries->where('cca2', strtoupper($this->attributes['alpha_code']))->first();

        if(! is_null($country) && $country->contains('name')) {
            return $this->getNativeName($this->attributes['designation'], $country);
        }

        return $this->attributes['designation'];
    }

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

    /**
     * Custom functions
     */

    /**
     * Get native country name
     *
     * @param string $designation
     * @param PragmaRXCollection $country
     * @return mixed
     */
    public function getNativeName(string $designation, PragmaRXCollection $country)
    {
        $commonName = $country->name->common;
        $languages = $country->languages ?? collect();
        $language = $languages->keys()->first() ?? null;
        $nativeNames = $country->name->native ?? null;

        if (filled($language) && filled($nativeNames) && filled($nativeNames[$language]) ?? null) {
            $native = $nativeNames[$language]['common'] ?? null;
        }

        if (blank($native ?? null) && filled($nativeNames)) {
            $native = $nativeNames->first()['common'] ?? null;
        }

        $native = $native ?? $commonName;

        if ($native !== $commonName && filled($native)) {
            return $native;
        }

        return $designation;
    }
}
