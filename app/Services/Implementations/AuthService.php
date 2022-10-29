<?php
namespace App\Services\Implementations;

use App\Models\User;
use App\Helpers\HelperFunctions;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\IAuthService;
use App\Contract\Responses\DefaultApiResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService implements IAuthService
{

    public DefaultApiResponse $response;
    public function __construct()
    {
        $this->response = new DefaultApiResponse();
    }

    public function register(RegisterRequest $request): DefaultApiResponse
    {
        $encryptedPassword = HelperFunctions::encryptValue($request->password);
        $userInstance = new User();
        $userInstance->AddUser($request,$encryptedPassword);

        $this->response->responseCode = '0';
        $this->response->message = "New User Created";
        $this->response->isSuccessful = true;
        // $this->response->data = 
        return $this->response;
    }

    public function login(LoginRequest $request): DefaultApiResponse
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            $this->response->responseCode = '1';
            $this->response->message = "Invalid Credentials";
            return $this->response;
        }
        //check password
        $decryptedPassFromDB = HelperFunctions::decryptValue($user->password);
        
        $isPasswordValid = HelperFunctions::compareValues($decryptedPassFromDB, $request->password);
        if (!$isPasswordValid) {
            $this->response->responseCode = '1';
            $this->response->message = "Invalid Credentials";
            return $this->response;
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        Log::info($token);

        $this->response->responseCode = '0';
        $this->response->message = "User " . $user->firstname . " has successfully logged in!";
        $this->response->isSuccessful = true;
        $this->response->data = [
            'token' => $token
        ];
        return $this->response;
    }
}
