<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;

class Sentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'en',
        'bn',
        'context',
        'subcontext',
        'source',
        'link_1',
        'link_2',
        'link_3',
        'note_type',
        'note',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
