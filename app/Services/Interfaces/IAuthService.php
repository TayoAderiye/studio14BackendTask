<?php

namespace App\Services\Interfaces;

use App\Http\Requests\RegisterRequest;
use App\Contract\Responses\DefaultApiResponse;
use App\Http\Requests\LoginRequest;

interface IAuthService 
{
    public function register(RegisterRequest $request): DefaultApiResponse;
    public function login(LoginRequest $request): DefaultApiResponse;
}