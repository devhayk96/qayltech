<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Country\StoreRequest;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CountriesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('hasAccess')->except('show');
    }

    /**
     * Return a listing of the countries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $countries = Country::query();
        if ($countryName = $request->get('name')) {
            $countries->where('name', 'LIKE', $countryName .'%');
        }
        return $this->sendResponse($countries->get(), 'Countries List');
    }

    /**
     * Store a newly created country in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $countryUser = User::create([
                'role_id' => Role::ALL['country'],
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make(Str::random(8))
            ]);

            $country = Country::create([
                'name' => $request->get('name'),
                'user_id' => $countryUser->id
            ]);

            DB::commit();
            return $this->sendResponse($countryUser, 'Country user successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }


    /**
     * Return the specified country.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if ($country = Country::find($id)) {
            return $this->sendResponse($country, $country->name);
        }

        return $this->sendError('Country not found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
