<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    /**
     * Display a listing of the favourite movies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $movies = Movie::all();

        return response()->json($movies);
    }

    /**
     * Display a listing of the popular movies.
     *
     * @return Illuminate\View\View
     */
    public function indexFavourites()
    {
        return view('pages.favourite');
    }

    /**
     * Display a listing of the popular movies.
     *
     * @return Illuminate\View\View
     */
    public function indexPopular()
    {
        return view('pages.popular');
    }

    /**
     * Display a listing of the latest movies.
     *
     * @return Illuminate\View\View
     */
    public function indexLatest()
    {
        return view('pages.latest');
    }

    /**
     * Display a listing of the latest movies.
     *
     * @return Illuminate\View\View
     */
    public function indexToprated()
    {
        return view('pages.toprated');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function search($id)
    {
        $json = json_decode(file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&query=$id"));
        return json_encode($json);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $movies = Movie::all()->pluck('imdb_id');
        $movie = json_decode($request->movie, true);
        foreach($movies as $item) {
            if ($item == $movie['id']) {
                return [
                    'result' => 'Фильм уже в списке'
                ];
            }
        }
        if (count($movie)) {
            $movie['imdb_id'] = $movie['id'];
            Movie::create($movie);

            $answer = [
                'result' => 'Фильм добавлен'
            ];
        }
        else {
            $answer = [
                'error' => 'Фильм не был добавлен'
            ];
        }

        return $answer;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('pages.movie');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();

            $answer = [
                'result' => 'Фильм удалён из избранного'
            ];
        }
        catch (ModelNotFoundException $exception) {
            $answer = [
                'error' => 'Фильм не найден'
            ];
        }

        return response()->json($answer);
    }
}
