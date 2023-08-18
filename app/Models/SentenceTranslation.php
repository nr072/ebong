<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sentence;

class SentenceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'lang',
        'sentence_id',
    ];

    public function sentence()
    {
        return $this->belongsTo(Sentence::class);
    }
}
