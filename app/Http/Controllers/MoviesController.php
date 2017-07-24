<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller
{
    public function index(Request $request, $id) 
    {
        $moviesRow = DB::table('movies')->where(['userId' => $id])->first();

        $movies = [];
        if ($moviesRow) {
            $moviesJsonString = $moviesRow->movies;

            $movies = json_decode($moviesJsonString, true);
        }

       return response()->json([
        'result' => $movies
       ], 200);
    }
    
    public function store(Request $request, $id)
    {
        $encodedMovies = $request->get('movies');

        $movies = json_decode($encodedMovies, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'message' => 'Malformed json data'
            ]);
        }

        $moviesRow = DB::table('movies')->where(['userId' => $id])->first();

        if ($moviesRow) {
            DB::table('movies')
                ->where(['userId' => $id])
                ->update(['movies' => $encodedMovies]);
        }
        else {
            DB::table('movies')
                ->insert([
                    'userId' => $id,
                    'movies' => $encodedMovies
                ]);
        }

        return response()->json([
            'result' => $movies
        ], 200);
    }
}
