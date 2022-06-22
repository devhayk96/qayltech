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
        $this->middleware(["permission:{$this->permissionKeyName} view,api"])->only('index');
        $this->middleware(["permission:{$this->permissionKeyName} viewPersonal,api"])->only('show');
        $this->middleware(["permission:{$this->permissionKeyName} create,api"])->only(['store']);
        $this->middleware(["permission:{$this->permissionKeyName} update,api"])->only(['update']);
        $this->middleware(["permission:{$this->permissionKeyName} delete,api"])->only(['delete']);
        $this->middleware(["permission:{$this->permissionKeyName} destroy,api"])->only(['destroy']);
        $this->middleware(["permission:{$this->permissionKeyName} restore,api"])->only(['restore']);
    }

    abstract protected function resourceName(): string;

    abstract protected function roleName(): string;

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

    protected function removeResource($userId, $action, $actionMessage)
    {
        if ($user = User::withTrashed()->find($userId)) {
            if ($user->role()->id == Role::ALL['super_admin']) {
                return $this->sendError(["You can't {$actionMessage} the super admin user"], 403);
            } elseif ($user->role()->id != Role::ALL[$this->roleName()]) {
                return $this->sendError(["User role is not ". $this->roleName()], 403);
            }

            if ($action == 'restore' && !$user->trashed()) {
                return $this->sendError("The ". $this->roleName() ." user can't be restored, because it hasn't been archived", 400);
            }

            try {
                $user->$action();
            } catch (\Exception $exception) {
                return $this->sendError([
                    "Something went wrong. Please try again or contact the administration"
                ], 403);
            }
            return $this->sendResponse([], $this->roleName() ." user {$actionMessage}d");
        }

        return $this->sendError($this->roleName() ." user not found");
    }
}
