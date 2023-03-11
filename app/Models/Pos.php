<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Word;

class Pos extends Model
{
    use HasFactory;

    protected $table = 'poses';

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
