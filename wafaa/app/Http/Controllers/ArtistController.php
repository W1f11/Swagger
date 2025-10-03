<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Resources\ArtistResource;


/**
 * @OA\Schema(
 *     schema="Artist",
 *     type="object",
 *     required={"id", "name", "genre", "country"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="genre", type="string", example="Rock"),
 *     @OA\Property(property="country", type="string", example="USA")
 * )
 */
class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/artists",
     *     summary="Liste des artistes",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="genre",
     *         in="query",
     *         description="Filtrer par genre",
     *         required=false,
     *         @OA\Schema(type="string", example="Rock")
     *     ),
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         description="Filtrer par pays",
     *         required=false,
     *         @OA\Schema(type="string", example="France")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Recherche partielle par nom",
     *         required=false,
     *         @OA\Schema(type="string", example="John")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'artistes par page (pagination)",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des artistes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Artist"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Artist::query();

        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $perPage = $request->get('per_page', 10);
        return ArtistResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/artists",
     *     summary="Créer un nouvel artiste",
     *     tags={"Artists"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "genre", "country"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="genre", type="string", example="Pop"),
     *             @OA\Property(property="country", type="string", example="USA")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Artist created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Artist")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string',
            'genre'   => 'required|string',
            'country' => 'required|string',
        ]);
        $artist = Artist::create($validated);
        return new ArtistResource($artist);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/artists/{id}",
     *     summary="Afficher un artiste",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist details",
     *         @OA\JsonContent(ref="#/components/schemas/Artist")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artist not found"
     *     )
     * )
     */
    public function show(Artist $artist)
    {
        return new ArtistResource($artist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/artists/{id}",
     *     summary="Mettre à jour un artiste",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="genre", type="string", example="Jazz"),
     *             @OA\Property(property="country", type="string", example="Canada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Artist")
     *     )
     * )
     */
    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name'    => 'sometimes|string',
            'genre'   => 'sometimes|string',
            'country' => 'sometimes|string',
        ]);
        $artist->update($validated);
        return new ArtistResource($artist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/artists/{id}",
     *     summary="Supprimer un artiste",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Artist deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response()->json(['message' => 'Artist deleted successfully']);
    }

    /**
     * Get artist albums.
     *
     * @OA\Get(
     *     path="/artists/{id}/albums",
     *     summary="Récupérer les albums d’un artiste",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artist albums retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="artist", ref="#/components/schemas/Artist"),
     *             @OA\Property(
     *                 property="albums",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Album")
     *             )
     *         )
     *     )
     * )
     */
    public function albums(Artist $artist)
    {
        $artist->load('albums');
        return response()->json([
            'artist' => new ArtistResource($artist),
            'albums' => $artist->albums
        ]);
    }
}
