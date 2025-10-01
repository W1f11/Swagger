<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Album",
 *   type="object",
 *   title="Album",
 *   required={"title", "year", "artist_id"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="Album Title"),
 *   @OA\Property(property="year", type="integer", example=2025),
 *   @OA\Property(property="artist_id", type="integer", example=1)
 * )
 */
class Album extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'year',
        'artist_id',
    ];


    public function artist(){
        return $this->belongsTo(Artist::class);
    }



    public function songs(){
        return $this->hasMany(Song::class);
    }
}
