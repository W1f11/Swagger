<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'duration' => $this->duration,
            'album'    => [
                'id'     => $this->album->id ?? null,
                'title'  => $this->album->title ?? null,
                'artist' => $this->album->artist->name ?? null,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
