<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MovieController::class, 'indexPopular']);

Route::get('/latest', [MovieController::class, 'indexLatest']);

Route::get('/toprated', [MovieController::class, 'indexToprated']);

Route::get('/favourites', [MovieController::class, 'indexFavourites']);

Route::get('/search/{id}', [MovieController::class, 'search']);

Route::post('/add', [MovieController::class, 'store']);

Route::get('/list', [MovieController::class, 'index']);

Route::get('/{id}', [MovieController::class, 'show']);

Route::delete('/{id}', [MovieController::class, 'destroy']);
