<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HarvestLog extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'batch_id',
        'logged_by',
        'harvest_date',
        'harvest_type',
        'weight_kg',
        'total_pcs',
        'fish_size',
        'price_per_kg',
        'total_revenue',
        'buyer_name',
        'notes',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}