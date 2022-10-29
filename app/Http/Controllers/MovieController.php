<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Interfaces\IMovieService;
use App\Http\Requests\CreateMovieListRequest;
use App\Contract\Responses\DefaultApiResponse;

class MovieController extends Controller
{
    
    public DefaultApiResponse $response;
    public IMovieService $iMovieService;
    public function __construct(IMovieService $iMovieService)
    {
        $this->response = new DefaultApiResponse();
        $this->iMovieService = $iMovieService;
    }

    public function AddToMovieList(CreateMovieListRequest $request): JsonResponse
    {
        try {
            $response = $this->iMovieService->createMovieList($request);
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

    public function getMovieForUser(Request $request): JsonResponse
    {
        try {
            $response = $this->iMovieService->getMovieListForUser($request);
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

    public function deleteFromMovieList(Request $request ,$movieId): JsonResponse
    {
        try {
            $response = $this->iMovieService->deleteMovieFromList($request,$movieId);
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

    public function getMovies(): JsonResponse
    {
        try {
            $response = $this->iMovieService->getAllMovies();
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
