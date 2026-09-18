<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_or_email',
        'status',
        'tenant_type',
        'identity_number',
        'address',
        'logo_path',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'subscription_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'subscription_expires_at' => 'datetime',
        ];
    }

    // Accessor agar pengecekan $tenant->is_active atau $tenant->is_active === true bisa digunakan
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['aktif', 'uji coba']);
    }

    // Scope helper untuk query Tenant::active()
    public function scopeActive($query)
    {
        return $query->where('status', ['aktif', 'uji coba']);
    }

    // Scope helper untuk query Tenant::suspended()
    public function scopeSuspended($query)
    {
        return $query->where('status', ['masa tenggang', 'diblokir']);
    }

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}