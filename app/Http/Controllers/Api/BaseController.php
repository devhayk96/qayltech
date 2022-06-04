<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected $permissionKeyName;

    public function __construct()
    {
        $this->permissionKeyName = $permissionKeyName = $this->resourceName();
        $this->middleware(["permission:{$permissionKeyName} view,api"])->only('index');
        $this->middleware(["permission:{$permissionKeyName} viewPersonal,api"])->only('show');
        $this->middleware(["permission:{$permissionKeyName} create,api"])->only(['create', 'store']);
        $this->middleware(["permission:{$permissionKeyName} update,api"])->only(['edit', 'update']);
        $this->middleware(["permission:{$permissionKeyName} delete,api"])->only(['delete', 'destroy']);
    }

    abstract protected function resourceName(): string;

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
}
