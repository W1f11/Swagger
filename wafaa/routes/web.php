<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Duplicate artist routes for non-/api access (serve JSON)
use App\Http\Controllers\ArtistController;

Route::middleware('api')->group(function () {
    Route::get('artists', [ArtistController::class, 'index']);
    Route::post('artists', [ArtistController::class, 'store']);
    Route::get('artists/{artist}', [ArtistController::class, 'show']);
    Route::put('artists/{artist}', [ArtistController::class, 'update']);
    Route::patch('artists/{artist}', [ArtistController::class, 'update']);
    Route::delete('artists/{artist}', [ArtistController::class, 'destroy']);
});
