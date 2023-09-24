<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sentence;
use App\Models\Pos;
use App\Models\Cluster;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'en',
        'pos_id',
        'cluster_id',
    ];

    public function sentences()
    {
        return $this->belongsToMany(Sentence::class);
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
}
