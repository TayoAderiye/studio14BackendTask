<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\CreateMovieListRequest;
use App\Contract\Responses\DefaultApiResponse;

interface IMovieService 
{
    public function createMovieList(CreateMovieListRequest $request): DefaultApiResponse;
    public function getMovieListForUser(Request $request): DefaultApiResponse;
    public function deleteMovieFromList(Request $request, $movieId): DefaultApiResponse;
    public function getAllMovies(): DefaultApiResponse;
}