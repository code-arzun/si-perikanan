<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'contact_id',
        'cashflow_category_id', // Replacing string 'category'
        'type',
        'amount',
        'transaction_date',
        'description',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // Relasi ke Master Kategori Cashflow
    public function category()
    {
        return $this->belongsTo(CashflowCategory::class, 'cashflow_category_id');
    }
}