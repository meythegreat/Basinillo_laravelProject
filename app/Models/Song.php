<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'artist',
        'genre_id',
        'duration_seconds',
        'release_year',
        'notes',
        'photo',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
