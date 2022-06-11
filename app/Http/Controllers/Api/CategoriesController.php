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
     * Archive the specified category in database.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Category::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Category archived successfully');
        }

        return $this->sendError('Category not found');
    }

    public function delete($id)
    {
        if (Category::query()->where('id', $id)->forceDelete()) {
            return $this->sendResponse([], 'Category deleted successfully');
        }

        return $this->sendError('Category not found');
    }

    public function restore($id)
    {
        if (Category::query()->where('id', $id)->restore()) {
            return $this->sendResponse([], 'Category restored successfully');
        }

        return $this->sendError('Category not found');
    }
}
