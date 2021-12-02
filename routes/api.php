<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Platform\CountryController;
use App\Http\Controllers\Api\Platform\PhoneCodeController;
use App\Http\Controllers\Api\Platform\UserController;
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

/**
 * New routes
 */
Route::group(['prefix' => 'v1', 'middleware' => 'intl'], function () {

    /**
     * Authentication routes
     */
    Route::group(['prefix' => 'auth'], function ($router) {
        $router->middleware('format-phone')->post('', [LoginController::class, 'authenticate']);
        $router->middleware('format-phone')->post('/registration', [RegisterController::class, 'registration']);

        Route::group(["middleware" => "auth:sanctum"], function ($router) {
            $router->get('user', [AuthController::class, 'user']);
            $router->put('logout', [AuthController::class, 'logout']);
        });
    });

    /**
     * Otp
     */
    Route::group(['prefix' => 'otp'], function ($router) {
        Route::group(["middleware" => "format-phone"], function ($router) {
            $router->post('send', [PhoneCodeController::class, 'send']);
            $router->put('verify', [PhoneCodeController::class, 'verify']);
        });
    });

    /**
     * Protected routes
     */
    Route::group(["middleware" => "auth:sanctum"], function ($router) {
    });

    /**
     * Public routes
     */
    Route::group(['prefix' => 'public'], function ($router) {
        Route::group(['prefix' => 'countries'], function ($router) {
            $router->get('', [CountryController::class, 'allSupportedCountries']);
        });

        Route::group(['prefix' => 'users'], function ($router) {
            $router->get('email/{email}', [UserController::class, 'findByEmail']);
            $router->post('verify-telephone', [UserController::class, 'findByTelephone']);
        });
    });

});
