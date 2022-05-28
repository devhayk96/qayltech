<?php

use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\CountriesController;
use App\Http\Controllers\API\DevicesController;
use App\Http\Controllers\API\DoctorsController;
use App\Http\Controllers\API\HospitalsController;
use App\Http\Controllers\API\OrganizationsController;
use App\Http\Controllers\API\PatientsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

Route::middleware('auth:api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::resource('categories', CategoriesController::class);
    Route::resource('countries', CountriesController::class);
    Route::resource('devices', DevicesController::class);
    Route::resource('doctors', DoctorsController::class);
    Route::resource('hospitals', HospitalsController::class);
    Route::resource('organizations', OrganizationsController::class);
    Route::resource('patients', PatientsController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
Route::resource('countries', CountriesController::class);
Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('login', [AuthController::class, 'login']);
//    Route::post('register','Auth\ApiAuthController@register');
});
