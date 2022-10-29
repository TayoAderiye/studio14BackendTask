<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\IAuthService;
use App\Contract\Responses\DefaultApiResponse;

class AuthController extends Controller
{
    public DefaultApiResponse $response;
    public IAuthService $iAuthService;
    public function __construct(IAuthService $iAuthService)
    {
        $this->response = new DefaultApiResponse();
        $this->iAuthService = $iAuthService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $response = $this->iAuthService->register($request);
            if ($response->isSuccessful) {
                return response()->json($response, 201);
            }
            return response()->json($response, 400);
        } catch (\Exception $e) {
            $this->response->message = 'Processing Failed, Contact Support';
            $this->response->error = $e->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->iAuthService->login($request);
            if ($response->isSuccessful) {
                return response()->json($response, 200);
            }
            return response()->json($response, 400);
        } catch (\Exception $e) {
            $this->response->message = 'Processing Failed, Contact Support';
            $this->response->error = $e->getMessage();
            return response()->json($this->response, 500);
        }
    }
}
