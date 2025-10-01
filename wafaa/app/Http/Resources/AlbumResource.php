<?php

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Display a listing of the resource with optional filters.
     */
    public function index(Request $request)
    {
        $query = Album::query();

        // Filtrer par année
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Filtrer par artiste
        if ($request->has('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        return AlbumResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created album.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'year' => 'required|integer',
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album = Album::create($validated);

        return new AlbumResource($album->load('artist'));
    }

    /**
     * Display the specified album.
     */
    public function show(Album $album)
    {
        return new AlbumResource($album->load('artist'));
    }

    /**
     * Update the specified album.
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'year' => 'sometimes|integer',
            'artist_id' => 'sometimes|exists:artists,id',
        ]);

        $album->update($validated);

        return new AlbumResource($album->load('artist'));
    }

    /**
     * Remove the specified album.
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(['message' => 'Album deleted successfully']);
    }

    /**
     * Get all songs of a given album.
     */
    public function songs(Album $album)
    {
        $album->load('songs');

        return response()->json([
            'album' => new AlbumResource($album),
            'songs' => $album->songs
        ]);
    }
}
