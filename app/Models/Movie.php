<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'imdb_id', 'poster_path', 'title', 'overview', 'release_date', 'vote_average'
    ];
}
