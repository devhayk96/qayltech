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
    Route::post('categories-delete/{id}', [CategoriesController::class, 'delete']);
    Route::post('categories-destroy/{id}', [CategoriesController::class, 'destroy']);
    Route::post('categories-restore/{id}', [CategoriesController::class, 'restore']);

    Route::apiResource('countries', CountriesController::class);
    Route::post('countries-delete/{id}', [CountriesController::class, 'delete']);
    Route::post('countries-destroy/{id}', [CountriesController::class, 'destroy']);
    Route::post('countries-restore/{id}', [CountriesController::class, 'restore']);

    Route::apiResource('devices', DevicesController::class);
    Route::post('devices-delete/{id}', [DevicesController::class, 'delete']);
    Route::post('devices-destroy/{id}', [DevicesController::class, 'destroy']);
    Route::post('devices-restore/{id}', [DevicesController::class, 'restore']);

    Route::apiResource('doctors', DoctorsController::class);
    Route::post('doctors-delete/{id}', [DoctorsController::class, 'delete']);
    Route::post('doctors-destroy/{id}', [DoctorsController::class, 'destroy']);
    Route::post('doctors-restore/{id}', [DoctorsController::class, 'restore']);

    Route::apiResource('hospitals', HospitalsController::class);
    Route::post('hospitals-delete/{id}', [HospitalsController::class, 'delete']);
    Route::post('hospitals-destroy/{id}', [HospitalsController::class, 'destroy']);
    Route::post('hospitals-restore/{id}', [HospitalsController::class, 'restore']);

    Route::apiResource('organizations', OrganizationsController::class);
    Route::post('organizations-delete/{id}', [OrganizationsController::class, 'delete']);
    Route::post('organizations-destroy/{id}', [OrganizationsController::class, 'destroy']);
    Route::post('organizations-restore/{id}', [OrganizationsController::class, 'restore']);

    Route::apiResource('patients', PatientsController::class);
    Route::post('patients-delete/{id}', [PatientsController::class, 'delete']);
    Route::post('patients-destroy/{id}', [PatientsController::class, 'destroy']);
    Route::post('patients-restore/{id}', [PatientsController::class, 'restore']);

    Route::group(['prefix' => 'patients/{patient}'], function() {
        Route::group(['prefix' => 'additional-infos'], function() {
            Route::get('', [PatientsController::class, 'additionalInfos']);
            Route::post('', [PatientsController::class, 'createAdditionalInfo']);
            Route::put('{id}', [PatientsController::class, 'updateAdditionalInfo']);
        });
    });
    Route::get('profile', [UserController::class, 'profile']);
    Route::post('assign-patient', [PatientsController::class, 'assignPatient']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('login', [AuthController::class, 'login']);
//    Route::post('register','Auth\ApiAuthController@register');
});
