<?php

use App\Http\Controllers\Api\Platform\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * New routes
 */
Route::group(['prefix' => 'v1', 'middleware' => 'intl'], function () {

    /**
     * Public routes
     */
    Route::group(['prefix' => 'public'], function ($router) {
        Route::group(['namespace' => 'Api\Platform'], function ($router) {

            Route::group(['prefix' => 'countries'], function ($router) {
                Route::get('', [CountryController::class, 'allSupportedCountries']);
            });

        });
    });

});
