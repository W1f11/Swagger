<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('artists' , ArtistController::class);
Route::apiResource('albums' , AlbumController::class);
Route::get('songs/search', [SongController::class, 'search']);
Route::apiResource('songs' , SongController::class);
Route::get('artistes/{artist}/albums', [ArtistController::class, 'albums']);
Route::get('albums/{album}/songs', [AlbumController::class, 'songs']);
