<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant; // <-- Isolasi Multi-Tenant Otomatis

    protected $attributes = [
        'seed_price_per_unit' => 0,
    ];

    protected $fillable = [
        'tenant_id',
        'pond_id',
        'fish_species_id',
        'batch_code',
        'start_date',
        'estimated_harvest_date',
        'actual_harvest_date',
        'initial_seed_count',
        'initial_avg_weight_g',
        'initial_total_weight_kg',
        'seed_price_per_unit',
        'target_fcr',
        'target_survival_rate',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date'             => 'date',
            'estimated_harvest_date' => 'date',
            'actual_harvest_date'    => 'date',
            'initial_seed_count'     => 'integer',
            'initial_avg_weight_g'   => 'decimal:2',
            'initial_total_weight_kg'=> 'decimal:2',
        ];
    }

    public function pond()
    {
        return $this->belongsTo(Pond::class);
    }

    public function waterQualityLog()
    {
        return $this->hasMany(WaterQualityLog::class);
    }

    public function fishSpecies()
    {
        return $this->belongsTo(FishSpecies::class);
    }

    public function mortalityLog()
    {
        return $this->hasMany(MortalityLog::class);
    }

    public function dailyFeedLog()
    {
        return $this->hasMany(DailyFeedLog::class);
    }
    
    public function samplingLog()
    {
        return $this->hasMany(SamplingLog::class);
    }

    public function treatmentLog()
    {
        return $this->hasMany(TreatmentLog::class);
    }

    public function harvestLog()
    {
        return $this->hasMany(HarvestLog::class);
    }
}