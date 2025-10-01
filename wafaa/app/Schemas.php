<?php

/**
 * @OA\Schema(
 *     schema="Error422",
 *     type="object",
 *     required={"message", "errors"},
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         additionalProperties=@OA\Property(type="array", @OA\Items(type="string")),
 *         example={"title": {"The title field is required."}, "artist_id": {"The artist_id field is required."}}
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="Error404",
 *     type="object",
 *     required={"message"},
 *     @OA\Property(property="message", type="string", example="Resource not found.")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Album",
 *     type="object",
 *     required={"id", "title", "year", "artist_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Parachutes"),
 *     @OA\Property(property="year", type="integer", example=2000),
 *     @OA\Property(property="artist_id", type="integer", example=1)
 * )
 */

/**
 * @OA\Schema(
 *     schema="Artist",
 *     type="object",
 *     required={"id", "name", "genre", "country"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Coldplay"),
 *     @OA\Property(property="genre", type="string", example="Rock"),
 *     @OA\Property(property="country", type="string", example="UK")
 * )
 */
