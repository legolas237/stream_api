<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Platform\CountryController;
use App\Http\Controllers\Api\Platform\UserController;
use App\Models\User;
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
     * Authentication routes
     */
    Route::group(['prefix' => 'auth'], function ($router) {
        $router->post('', [LoginController::class, 'authenticate']);
    });

    /**
     * Protected routes
     */
    Route::group(["middleware" => "auth:sanctum"], function ($router) {
        $router->get('user', [AuthController::class, 'user']);
    });

    /**
     * Public routes
     */
    Route::group(['prefix' => 'public'], function ($router) {
        Route::group(['prefix' => 'countries'], function ($router) {
            $router->get('', [CountryController::class, 'allSupportedCountries']);
        });

        Route::group(['prefix' => 'users'], function ($router) {
            $router->get('username/{userName}', [UserController::class, 'findByUsername']);
            $router->get('email/{email}', [UserController::class, 'findByEmail']);
        });
    });

});
