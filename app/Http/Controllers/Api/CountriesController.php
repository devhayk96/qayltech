<?php

namespace App\Http\Controllers\API;

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
//        $this->middleware('hasAccess')->only('index', 'store', 'update');
    }

    /**
     * Return a listing of the countries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $countries = Country::query();
        if ($countryName = $request->get('name')) {
            $countries->where('name', 'LIKE', $countryName .'%');
        }
        return $this->sendResponse($countries, 'Countries List');
    }

    /**
     * Store a newly created country in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified country.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
