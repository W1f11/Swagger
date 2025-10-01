<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Resources\SongResource;

class SongController extends Controller
{
   /**
 * @OA\Get(
 *     path="/api/songs",
 *     summary="Liste des chansons",
 *     tags={"Songs"},
 *     @OA\Parameter(
 *         name="min_duration",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Durée minimale (en secondes)"
 *     ),
 *     @OA\Parameter(
 *         name="max_duration",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer"),
 *         description="Durée maximale (en secondes)"
 *     ),
 *     @OA\Parameter(
 *         name="title",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string"),
 *         description="Recherche par titre"
 *     ),
 *     @OA\Response(response=200, description="Liste paginée des chansons")
 * )
 *
 * @OA\Get(
 *     path="/api/songs/{id}",
 *     summary="Afficher une chanson",
 *     tags={"Songs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Chanson trouvée"),
 *     @OA\Response(response=404, description="Chanson non trouvée")
 * )
 *
 * @OA\Post(
 *     path="/api/songs",
 *     summary="Créer une chanson",
 *     tags={"Songs"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","duration","album_id"},
 *             @OA\Property(property="title", type="string", example="Shape of You"),
 *             @OA\Property(property="duration", type="integer", example=210),
 *             @OA\Property(property="album_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Chanson créée avec succès"),
 *     @OA\Response(response=400, description="Requête invalide")
 * )
 *
 * @OA\Put(
 *     path="/api/songs/{id}",
 *     summary="Mettre à jour une chanson",
 *     tags={"Songs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Perfect"),
 *             @OA\Property(property="duration", type="integer", example=240),
 *             @OA\Property(property="album_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Chanson mise à jour"),
 *     @OA\Response(response=404, description="Chanson non trouvée")
 * )
 *
 * @OA\Delete(
 *     path="/api/songs/{id}",
 *     summary="Supprimer une chanson",
 *     tags={"Songs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Chanson supprimée"),
 *     @OA\Response(response=404, description="Chanson non trouvée")
 * )
 *
 * @OA\Get(
 *     path="/api/songs/search",
 *     summary="Rechercher des chansons par titre ou artiste",
 *     tags={"Songs"},
 *     @OA\Parameter(
 *         name="q",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Mot-clé (titre ou artiste)"
 *     ),
 *     @OA\Response(response=200, description="Résultats trouvés"),
 *     @OA\Response(response=404, description="Aucun résultat trouvé")
 * )
 */

    public function index(Request $request)
{
    $query = Song::with('album.artist');

    if ($request->has('min_duration')) {
        $query->where('duration', '>=', $request->min_duration);
    }

    if ($request->has('max_duration')) {
        $query->where('duration', '<=', $request->max_duration);
    }

    if ($request->has('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    return SongResource::collection($query->paginate(10));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'duration' => 'required|integer',
            'album_id' => 'required|exists:albums,id',
        ]);
        $song = Song::create($validated);

        return new SongResource($song->load('album.artist'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Song $song)
    {
        return new SongResource($song->load('album.artist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Song $song)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'title'=> 'sometimes|string',
            'duration'=> 'sometimes|integer',
            'album_id'=> 'sometimes|exists:albums,id',

        ]);

        $song->update($validated);

        return new SongResource($song->load('album.artist'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        $song->delete();

        return response()->json(['message' => 'Song deleted sucessfully']);
    }
    // GET /api/songs/search?title=...&artist=...

public function search(Request $request)
{
     $query = $request->input('q');

        // Vérifie si un paramètre q est fourni
        if (!$query) {
            return response()->json(['message' => 'Missing search query'], 400);
        }

        $songs = Song::where('title', 'like', "%{$query}%")->get();

        if ($songs->isEmpty()) {
            return response()->json(['message' => 'No songs found'], 404);
        }

        return response()->json($songs);
}

}
