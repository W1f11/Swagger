<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
        'id' => $this->id,
        'title' => $this->title,
        'duration' => $this->duration,
        'album' => $this->album ? [
            'id' => $this->album->id,
            'title' => $this->album->title,
            'year' => $this->album->year,
        ] : null,
        'artist' => $this->album && $this->album->artist ? [
            'id' => $this->album->artist->id,
            'name' => $this->album->artist->name,
            'genre' => $this->album->artist->genre,
            'country' => $this->album->artist->country,
        ] : null,
        'created_at' => $this->created_at ? $this->created_at->toDateString() : null,
        'updated_at' => $this->updated_at ? $this->updated_at->toDateString() : null,
    ];

    }
}
