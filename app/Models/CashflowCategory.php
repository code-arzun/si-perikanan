<?php

namespace App\Models;

use App\Enums\CashflowKeterangan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashflowCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'keterangan',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'keterangan' => CashflowKeterangan::class, // Mengubah string DB menjadi Enum secara otomatis
        ];
    }

    /**
     * Accessor untuk nama lengkap kategori
     * Contoh hasil: "Pembelian Pakan"
     */
    public function getFullNameAttribute(): string
    {
        $label = $this->keterangan instanceof CashflowKeterangan 
            ? $this->keterangan->label() 
            : ucfirst($this->keterangan);

        return $label . ' ' . $this->name;
    }

    /**
     * Scope untuk memfilter kategori Pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('type', 'pemasukan');
    }

    /**
     * Scope untuk memfilter kategori Pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('type', 'pengeluaran');
    }
}