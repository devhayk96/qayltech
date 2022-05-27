<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\HospitalsController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\PatientsController;
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
