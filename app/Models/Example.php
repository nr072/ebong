<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
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
        'term_id',
    ];



    public function terms()
    {
        return $this->belongsToMany(Term::class);
    }

}
