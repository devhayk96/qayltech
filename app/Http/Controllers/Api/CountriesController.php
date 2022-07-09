<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Country\StoreRequest;
use App\Http\Resources\CountryCollection;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Services\User\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountriesController extends BaseController
{
    protected function resourceName() : string
    {
        return 'countries';
    }

    protected function modelOrRoleName() : string
    {
        return 'country';
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

        if (is_super_admin()) {
            if ($countryId = $request->get('countryId')) {
                $countries->where('country_id', $countryId);
            }

        }


        return $this->sendResponse(new CountryCollection($countries->get()), 'Countries List');


//        $countries = Country::query();
//        if ($countryName = $request->get('name')) {
//            $countries->where('name', 'LIKE', $countryName .'%');
//        }
//        return $this->sendResponse($countries->get(), 'Countries List');
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
            $request->merge(['role_id' => Role::ALL['country']]);
            $countryUser = (new StoreService($request))->run();

            $country = Country::create([
                'name' => $countryUser['name'],
                'user_id' => $countryUser['id']
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
        if ($country = Country::with('organizations')->find($id)) {
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

}
