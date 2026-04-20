<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;
use Pgvector\Laravel\Distance;

class Paper extends Model
{
    use HasNeighbors; 

    public $timestamps = false; 
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $casts = [
        'embedding' => Vector::class,
    ];

    public function getRecommendations()
    {
        return $this->nearestNeighbors('embedding', Distance::Cosine)
                    ->where('id', '!=', $this->id)
                    ->take(5)
                    ->get();
    }
}