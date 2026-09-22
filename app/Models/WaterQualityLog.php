<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterQualityLog extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'batch_id', 'logged_by',
        'check_date', 'check_time_session', 'ph', 'do_mg_l', 'temperature_c',
        'salinity_ppt', 'transparency_cm', 'water_color', 'notes',
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