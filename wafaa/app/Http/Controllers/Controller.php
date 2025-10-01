<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="MusicBox API",
 *     version="1.0.0",
 *     description="API pour gérer les artistes, albums et chansons",
 *     @OA\Contact(
 *         email="admin@musicbox.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Serveur Local"
 * )
 */

// Inclure les schémas
include_once app_path('Schemas.php');

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}