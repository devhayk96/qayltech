<?php

use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\DevicesController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\HospitalsController;
use App\Http\Controllers\Api\OrganizationsController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
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

Route::get('/', [AuthController::class, 'index']);

Route::group(['middleware' => ['cors', 'json.response', 'auth:api']], function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('categories', CategoriesController::class);
    Route::apiResource('countries', CountriesController::class);
    Route::apiResource('devices', DevicesController::class);
    Route::apiResource('doctors', DoctorsController::class);
    Route::apiResource('hospitals', HospitalsController::class);
    Route::apiResource('organizations', OrganizationsController::class);
    Route::apiResource('patients', PatientsController::class);

    Route::group(['prefix' => 'patients/{patient}'], function() {
        Route::group(['prefix' => 'additional-infos'], function() {
            Route::get('', [PatientsController::class, 'additionalInfos']);
            Route::post('', [PatientsController::class, 'createAdditionalInfo']);
            Route::put('{id}', [PatientsController::class, 'updateAdditionalInfo']);
        });
    });
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('assign-patient', [PatientsController::class, 'assignPatient']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('login', [AuthController::class, 'login']);
//    Route::post('register','Auth\ApiAuthController@register');
});
