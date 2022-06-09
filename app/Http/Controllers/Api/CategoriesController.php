<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\ListRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends BaseController
{
    protected function resourceName() : string
    {
        return 'categories';
    }

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {
        $categories = Category::query()->where('type', $request->get('type'));

        return $this->sendResponse($categories->get(), 'List of categories');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created category in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $category = Category::create([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
            ]);

            DB::commit();
            return $this->sendResponse($category, 'Category successfully created');
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
     * Show the form for editing the specified resource.
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
        if (Category::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Category deleted successfully');
        }

        return $this->sendError('Category not found');
    }

    public function delete($id)
    {

    }

    public function restore($id)
    {

    }
}
