<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class CashTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->route('finance')) {
            $transaction = $this->route('finance');
            return auth()->check() && $transaction->tenant_id === auth()->user()->tenant_id;
        }

        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'category'         => 'required|string|max:100',
            'contact_id'       => 'nullable|exists:contacts,id',
            'description'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'             => 'Tipe transaksi wajib dipilih.',
            'amount.required'           => 'Nominal transaksi wajib diisi.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'category.required'         => 'Kategori transaksi wajib diisi.',
        ];
    }
}