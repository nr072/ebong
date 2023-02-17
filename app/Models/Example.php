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
        'link_1',
        'link_2',
        'link_3',
        'source',
        'term_id',
    ];



    public function term()
    {
        return $this->belongsTo(Term::class);
    }

}
