<?php
namespace App\Services\Implementations;

use App\Services\Interfaces\IMovieService;
use App\Http\Requests\CreateMovieListRequest;
use App\Contract\Responses\DefaultApiResponse;
use App\Helpers\HelperFunctions;
use App\Http\Resources\GetUserMovieResource;
use App\Models\Movie;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MovieService implements IMovieService
{
    public DefaultApiResponse $response;
    private $baseUrl;
    private $apiKey;
    public function __construct()
    {
        $this->response = new DefaultApiResponse();
        $this->baseUrl = env('BASE_URL');
        $this->apiKey = env('API_KEY');
    }
    public function createMovieList(CreateMovieListRequest $request): DefaultApiResponse
    {
        $userObject = HelperFunctions::getLoggedInUserV2($request);
        $movieModelInstance = new Movie();
        $movies = User::find($userObject->id)->movies();
        if ($movies->count() == 10) {
            $this->response->responseCode = '0';
            $this->response->message = "Maximum Amount Reached";
            $this->response->isSuccessful = true;
            // $this->response->data = 
            return $this->response;
        }
        $movieModelInstance->AddMovie($request,$userObject->id);
        $this->response->responseCode = '0';
        $this->response->message = "Movie Added for " . $userObject->firstname;
        $this->response->isSuccessful = true;
        // $this->response->data = 
        return $this->response;
    }

    public function getMovieListForUser(Request $request): DefaultApiResponse
    {
        $formattedData = [];
        $userObject = HelperFunctions::getLoggedInUserV2($request);
        $movies = User::find($userObject->id)->movies;
        foreach ($movies as $movie) {
            $newData = new GetUserMovieResource($movie);
            array_push($formattedData, $newData);
        }
        $this->response->responseCode = '0';
        $this->response->message = "Movie List Fetched";
        $this->response->isSuccessful = true;
        $this->response->data = $formattedData;
        return $this->response;
    }

    public function deleteMovieFromList(Request $request, $movieId): DefaultApiResponse
    {
        $userObject = HelperFunctions::getLoggedInUserV2($request);
        $movie = Movie::find($movieId);
        Log::info($movie);
        if (empty($movie)) {
            $this->response->responseCode = '1';
            $this->response->message = "Cannot Delete Movie that doesn't exist";
            return $this->response;
        }
        $isOwner = HelperFunctions::compareValues($userObject->id ,$movie->user_id);
        if (!$isOwner) {
            $this->response->responseCode = '1';
            $this->response->message = "Cannot Delete another User's movie";
            return $this->response;
        }
        $movie->delete();
        $this->response->responseCode = '0';
        $this->response->message = "Movie Successfully Deleted";
        $this->response->isSuccessful = true;
        return $this->response;
    }

    public function getAllMovies(): DefaultApiResponse
    {
        $allMovies = HelperFunctions::getMovieList($this->baseUrl, $this->apiKey);
        $faker = Factory::create();
        $formattedData = [];
        foreach ($allMovies['results'] as $key => $value) {
            $id = $value['id'];
            $name = $value['title'];
            $year = $value['release_date'];
            $genre = $faker->randomElement(['Action', 'Comedy', 'Romance', 'Thriller']);
            $data =  array('id' => $id ,'name' => $name, 'year' => $year, 'genre' => $genre);
            array_push($formattedData,$data);
        }
        $this->response->responseCode = '0';
        $this->response->message = "All Movies Fetched!!!";
        $this->response->isSuccessful = true;
        $this->response->data = $formattedData;
        return $this->response;
    }
}
