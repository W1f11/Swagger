<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use App\Http\Resources\AlbumResource;

/**
 * @OA\Get(
 *     path="/api/albums",
 *     summary="Liste des albums",
 *     tags={"Albums"},
 *     @OA\Parameter(
 *         name="year",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Filtrer par année"
 *     ),
 *     @OA\Parameter(
 *         name="artist_id",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Filtrer par ID d'artiste"
 *     ),
 *     @OA\Response(response=200, description="Liste paginée des albums")
 * )
 *
 * @OA\Get(
 *     path="/api/albums/{id}",
 *     summary="Afficher un album",
 *     tags={"Albums"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Album trouvé"),
 *     @OA\Response(response=404, description="Album non trouvé")
 * )
 *
 * @OA\Post(
 *     path="/api/albums",
 *     summary="Créer un nouvel album",
 *     tags={"Albums"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","year","artist_id"},
 *             @OA\Property(property="title", type="string", example="Divide"),
 *             @OA\Property(property="year", type="integer", example=2017),
 *             @OA\Property(property="artist_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Album créé avec succès"),
 *     @OA\Response(response=400, description="Requête invalide")
 * )
 *
 * @OA\Put(
 *     path="/api/albums/{id}",
 *     summary="Mettre à jour un album",
 *     tags={"Albums"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="No.6 Collaborations Project"),
 *             @OA\Property(property="year", type="integer", example=2019),
 *             @OA\Property(property="artist_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Album mis à jour"),
 *     @OA\Response(response=404, description="Album non trouvé")
 * )
 *
 * @OA\Delete(
 *     path="/api/albums/{id}",
 *     summary="Supprimer un album",
 *     tags={"Albums"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Album supprimé"),
 *     @OA\Response(response=404, description="Album non trouvé")
 * )
 *
 * @OA\Get(
 *     path="/api/albums/{id}/songs",
 *     summary="Lister les chansons d'un album",
 *     tags={"Albums"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Chansons de l'album"),
 *     @OA\Response(response=404, description="Album non trouvé")
 * )
 */

class AlbumController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Album::query()->with('artist');

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        return AlbumResource::collection($query->paginate(10));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album = Album::create($validated);
        return new AlbumResource($album->load('artist'));
    }


    public function show(Album $album)
    {
        return new AlbumResource($album->load('artist', 'songs'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer|min:1900|max:' . date('Y'),
            'artist_id' => 'sometimes|exists:artists,id',
        ]);

        $album->update($validated);
        return new AlbumResource($album->load('artist'));
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(['message' => 'Album deleted successfully']);
    }

    public function songs(Album $album)
    {
        $album->load('songs');
        return response()->json([
            'album' => new AlbumResource($album),
            'songs' => $album->songs
        ]);
    }
}