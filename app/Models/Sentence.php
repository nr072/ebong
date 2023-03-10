<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Word;

class Sentence extends Model
{
    use HasFactory;

    protected $fillable = ['en', 'bn'];

    public function words()
    {
        return $this->belongsToMany(Word::class);
    }
}
