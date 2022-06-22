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

Route::group(['middleware' => ['cors', 'auth:api']], function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('countries', CountriesController::class);
    Route::group(['prefix' => 'countries/{userId}'], function() {
        Route::post('delete', [CountriesController::class, 'delete']);
        Route::post('destroy', [CountriesController::class, 'destroy']);
        Route::post('restore', [CountriesController::class, 'restore']);
    });

    Route::apiResource('categories', CategoriesController::class);
    Route::group(['prefix' => 'categories/{id}'], function() {
        Route::post('delete', [CategoriesController::class, 'delete']);
        Route::post('destroy', [CategoriesController::class, 'destroy']);
        Route::post('restore', [CategoriesController::class, 'restore']);
    });

    Route::apiResource('devices', DevicesController::class);
    Route::group(['prefix' => 'devices/{id}'], function() {
        Route::post('delete', [DevicesController::class, 'delete']);
        Route::post('destroy', [DevicesController::class, 'destroy']);
        Route::post('restore', [DevicesController::class, 'restore']);
    });

    Route::apiResource('doctors', DoctorsController::class);
    Route::group(['prefix' => 'doctors/{userId}'], function() {
        Route::post('delete', [DoctorsController::class, 'delete']);
        Route::post('destroy', [DoctorsController::class, 'destroy']);
        Route::post('restore', [DoctorsController::class, 'restore']);
    });

    Route::apiResource('hospitals', HospitalsController::class);
    Route::group(['prefix' => 'hospitals/{userId}'], function() {
        Route::post('delete', [HospitalsController::class, 'delete']);
        Route::post('destroy', [HospitalsController::class, 'destroy']);
        Route::post('restore', [HospitalsController::class, 'restore']);
    });

    Route::apiResource('organizations', OrganizationsController::class);
    Route::group(['prefix' => 'organizations/{userId}'], function() {
        Route::post('delete', [OrganizationsController::class, 'delete']);
        Route::post('destroy', [OrganizationsController::class, 'destroy']);
        Route::post('restore', [OrganizationsController::class, 'restore']);
    });

    Route::apiResource('patients', PatientsController::class);
    Route::group(['prefix' => 'patients/{patient}'], function() {
        Route::post('delete', [PatientsController::class, 'delete']);
        Route::post('destroy', [PatientsController::class, 'destroy']);
        Route::post('restore', [PatientsController::class, 'restore']);

        Route::group(['prefix' => 'additional-infos'], function() {
            Route::get('', [PatientsController::class, 'additionalInfos']);
            Route::post('', [PatientsController::class, 'storeAdditionalInfo']);
        });

        Route::group(['prefix' => 'workout-infos'], function() {
            Route::get('', [PatientsController::class, 'workoutInfos']);
            Route::post('', [PatientsController::class, 'storeWorkoutInfo']);
        });
    });

    Route::get('profile', [UserController::class, 'profile']);
    Route::post('assign-patient', [PatientsController::class, 'assignPatient']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('login', [AuthController::class, 'login']);
//    Route::post('register','Auth\ApiAuthController@register');
});
