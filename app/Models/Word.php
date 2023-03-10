<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sentence;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'en',
        'pos'
    ];

    public function sentences()
    {
        return $this->belongsToMany(Sentence::class);
    }
}
