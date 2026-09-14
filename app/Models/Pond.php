<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Batch;

class Pond extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant; // <-- Isolasi Multi-Tenant Otomatis

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'shape',
        'length',
        'width',
        'diameter',
        'depth',
        'volume_m3',
        'pond_type_id',
        'status',
    ];

    // Auto calculate volume saat menyimpan jika tidak diisi manual
    protected static function booted()
    {
        static::saving(function ($pond) {
            if (! $pond->volume_m3) {
                // Perhitungan Kolam Bulat (Tabung)
                if ($pond->shape === 'bulat' && $pond->diameter && $pond->depth) {
                    $radius = $pond->diameter / 2;
                    $pond->volume_m3 = pi() * pow($radius, 2) * $pond->depth;
                }
                // Perhitungan Kolam Persegi & Persegi Panjang (Balok)
                elseif (in_array($pond->shape, ['persegi', 'persegi panjang']) && $pond->length && $pond->width && $pond->depth) {
                    $pond->volume_m3 = $pond->length * $pond->width * $pond->depth;
                }
            }
        });
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatch()
    {
        return $this->hasOne(Batch::class)->where('status', 'active');
    }

    public function pondType()
    {
        return $this->belongsTo(PondType::class);
    }
}