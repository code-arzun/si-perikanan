<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'brand',
        'protein_percentage',
        'pellet_size',
        'description',
    ];

    public function dailyFeedLogs()
    {
        return $this->hasMany(DailyFeedLog::class);
    }
}