<?php

namespace App\Models;

use App\Enums\TransactionUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'contact_id',
        'cashflow_category_id',
        'type',
        'unit_price',
        'quantity',
        'unit',
        'amount',
        'transaction_date',
        'description',
    ];

    protected $casts = [
        'unit' => TransactionUnit::class,
        'unit_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    // Hitung otomatis total amount sebelum insert/update
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaction) {
            $transaction->amount = $transaction->unit_price * $transaction->quantity;
        });
    }

    public function category()
    {
        return $this->belongsTo(CashflowCategory::class, 'cashflow_category_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}