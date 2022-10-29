<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;


class HelperFunctions
{
    public static function encryptValue($value)
    {
        // return Crypt::encryptString($pin);
        return encrypt($value);
    }

    public static function decryptValue($value)
    {
        return decrypt($value);
    }

    public static function compareValues($value1, $value2)
    {
        if ($value1 != $value2) {
            return false;
        }
        return true;
    }

    public static function getLoggedInUser($request)
    {
        $hashedToken = $request->header('Authorization');
        $hashedToken = explode(" ", $hashedToken);
        $token = PersonalAccessToken::findToken($hashedToken[1]);
        $user = $token->tokenable;
        return $user;
    }

    // public static function getUserEmailbyId($id)
    // {
    //     $user = User::where('id', $id)->first();
    //     return $user->email;
    // }

    public static function getLoggedInUserV2($request)
    {
        $hashedToken = $request->header('Authorization');
        if (empty($hashedToken)) {
            return "";
        }
        if (str_contains($hashedToken, 'Bearer')) {
            $hashedToken = explode(" ", $hashedToken);
            $token = PersonalAccessToken::findToken($hashedToken[1]);
            $user = $token->tokenable;
            return $user;
        }
        $hashedToken = explode(" ", $hashedToken);
        $token = PersonalAccessToken::findToken($hashedToken[0]);
        $user = $token->tokenable;
        return $user;
    }

    public static function getMovieList($baseurl, $apiKey)
    {
        $url = "{$baseurl}/3/movie/popular?api_key={$apiKey}&language=en-US&page=1";
        $x = new HelperFunctions();
        return $x->GetRequest($url);
    }

    public function GetRequest($url)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorzation' => env('AUTH_TOKEN')
        ])->get($url);
    }
}
