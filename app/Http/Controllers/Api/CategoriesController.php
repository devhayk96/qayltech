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

    protected function roleName() : string
    {
        return 'category';
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
     * @param $userId
     * @return JsonResponse
     */
    public function destroy($userId)
    {
        return $this->removeResource($userId, 'delete', 'archive');
    }

    /**
     * Permanently delete the specified category from database
     *
     * @param $userId
     * @return JsonResponse
     */
    public function delete($userId)
    {
        return $this->removeResource($userId, 'forceDelete', 'permanently delete');
    }

    /**
     * Restore temporary deleted(archived) category
     * @param $userId
     * @return JsonResponse
     */
    public function restore($userId)
    {
        return $this->removeResource($userId, 'restore', 'restore');
    }
}
