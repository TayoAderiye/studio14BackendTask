<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(array('prefix' => 'auth' ),  function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // ->middleware('auth:sanctum');
});

//protected routes
Route::group(['middleware' => ['auth:sanctum']] , function () {
    Route::group(array('prefix' => 'movies'), function () {
        Route::post('/addMovieList', [MovieController::class, 'AddToMovieList']);
        Route::get('/getUserMovieList', [MovieController::class, 'getMovieForUser']);
        Route::delete('/delete/{movieId}', [MovieController::class, 'deleteFromMovieList']);
        Route::get('/getAll', [MovieController::class, 'getMovies']);

        // Route::post('/logout', [AuthController::class, 'logout']);
    });
});
