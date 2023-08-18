<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\SentenceTranslation;

class Sentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'lang',
        'context',
        'subcontext',
        'project',
        'link_1',
        'link_2',
        'link_3',
        'note_type',
        'note',
        'needs_revision',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function translations()
    {
        return $this->hasMany(SentenceTranslation::class);
    }
}
