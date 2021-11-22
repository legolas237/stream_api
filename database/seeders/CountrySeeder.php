<?php

namespace Database\Seeders;

use Giggsey\Locale\Locale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use libphonenumber\PhoneNumberUtil;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $countries = $phoneUtil->getSupportedRegions();

        foreach ($countries as $countryCode) {
            $dialCode = $phoneUtil->getCountryCodeForRegion($countryCode);

            if(! DB::table('countries')->where('country_code', '=', strtoupper($countryCode))->exists()) {
                DB::table('countries')->insert([
                    'designation' => Locale::getDisplayRegion(
                        sprintf('-%s', strtoupper($countryCode)),
                        app()->getLocale()
                    ),
                    'country_code' => $dialCode,
                    'alpha_code' => strtoupper($countryCode),
                    'activated_at' => null,
                ]);
            }
        }
    }
}
