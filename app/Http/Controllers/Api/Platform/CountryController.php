<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{

    /**
     * All countries
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function allSupportedCountries()
    {
        return api_response(
            100,
            'Ok',
            Country::allCountries(false, true)
        );
    }

}
