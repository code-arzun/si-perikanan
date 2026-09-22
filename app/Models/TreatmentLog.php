<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentLog extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'batch_id',
        'logged_by',
        'treatment_date',
        'product_name',
        'dosage_amount',
        'dosage_unit',
        'purpose',
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