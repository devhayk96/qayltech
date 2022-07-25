<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected $permissionKeyName;

    public function __construct()
    {
        $this->permissionKeyName = $this->resourceName();
//        $this->middleware(["permission:{$this->permissionKeyName} view,api"])->only('index');
//        $this->middleware(["permission:{$this->permissionKeyName} viewPersonal,api"])->only('show');
        $this->middleware(["permission:{$this->permissionKeyName} create,api"])->only(['store']);
        $this->middleware(["permission:{$this->permissionKeyName} update,api"])->only(['update']);
        $this->middleware(["permission:{$this->permissionKeyName} delete,api"])->only(['delete']);
        $this->middleware(["permission:{$this->permissionKeyName} destroy,api"])->only(['destroy']);
        $this->middleware(["permission:{$this->permissionKeyName} restore,api"])->only(['restore']);
    }

    abstract protected function resourceName(): string;

    abstract protected function modelOrRoleName(): string;

    public function getResourceModel()
    {
        return new User();
    }

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $code = 404, $errorMessages = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    protected function removeResource($resourceId, $action, $actionMessage)
    {
        if ($user = $this->getResourceModel()->withTrashed()->find($resourceId)) {
            if ($user->role()->id == Role::ALL['super_admin']) {
                return $this->sendError(["You can't {$actionMessage} the super admin user"], 403);
            } elseif ($user->role()->id != Role::ALL[$this->modelOrRoleName()]) {
                return $this->sendError(["User role is not ". $this->modelOrRoleName()], 403);
            }

            if ($action == 'restore' && !$user->trashed()) {
                return $this->sendError("The ". $this->modelOrRoleName() ." user can't be restored, because it hasn't been archived", 400);
            }

            try {
                $roleModel = $this->getModel()->withTrashed()->where('user_id', $user->id)->first();
                $roleModel->$action();
                $user->$action();
            } catch (\Exception $exception) {
                return $this->sendError([
                    "Something went wrong. Please try again or contact the administration"
                ], 403);
            }
            return $this->sendResponse([], $this->modelOrRoleName() ." user {$actionMessage}d");
        }

        return $this->sendError($this->modelOrRoleName() ." user not found");
    }

    /**
     * Archive the specified resource in database.
     *
     * @param $resourceId
     * @return JsonResponse
     */
    public function destroy($resourceId)
    {
        return $this->removeResource($resourceId, 'delete', 'archive');
    }

    /**
     * Permanently delete the specified resource from database
     *
     * @param $resourceId
     * @return JsonResponse
     */
    public function delete($resourceId)
    {
        return $this->removeResource($resourceId, 'forceDelete', 'permanently delete');
    }

    /**
     * Restore temporary deleted(archived) resource
     * @param $resourceId
     * @return JsonResponse
     */
    public function restore($resourceId)
    {
        return $this->removeResource($resourceId, 'restore', 'restore');
    }
}
