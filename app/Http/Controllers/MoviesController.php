<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client as Mongo;

class MoviesController extends Controller
{
    public function index(Request $request, $id) 
    {
       $client = $this->_getMongoClient();

       $collection = $client->supermovies->movies;

       $result = $collection->findOne(['userId' => $id]);

       return response()->json([
        'result' => $result
       ], 200);
    }
    
    public function store(Request $request, $id)
    {
        $encodedMovies = $request->get('movies');

        $movies = json_decode($encodedMovies, true);

        $client = $this->_getMongoClient();

        $collection = $client->supermovies->movies;

        $query = ['userId' => $id];

        $entry = $collection->findOne($query);

        if (!$entry) {
            $collection->insertOne([
                'userId' => $id,
                'movies' => $movies
            ]);
        }
        else {
           $collection->findOneAndReplace($query, [
               'userId' => $id,
               'movies' => $movies
           ]);
        }

        return response()->json([
            'result' => $movies
        ], 200);
    }

    private function _getMongoClient()
    {
        return new Mongo("mongodb://localhost:27017");
    }
}
