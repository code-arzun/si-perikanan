<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SamplingLog extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'batch_id', 'logged_by',
        'sampling_date', 'sample_count_pcs', 'avg_weight_g', 'avg_length_cm', 'estimated_biomass_kg', 'notes',
    ];

    public function batch() { return $this->belongsTo(Batch::class); }
    public function user() { return $this->belongsTo(User::class, 'logged_by'); }
}