<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishSpecies extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latin_name',
        'description',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}