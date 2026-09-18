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
            'contact_id'           => ['nullable', 'exists:contacts,id'],
            'cashflow_category_id' => ['required', 'exists:cashflow_categories,id'],
            'type'                 => ['required', 'in:income,expense'],
            'amount'               => ['required', 'numeric', 'min:0'],
            'transaction_date'     => ['required', 'date'],
            'description'          => ['nullable', 'string', 'max:1000'],
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