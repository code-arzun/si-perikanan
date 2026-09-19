<?php

namespace App\Http\Requests\Tenant;

use App\Enums\TransactionUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'unit_price'           => ['required', 'numeric', 'min:0'],
            'quantity'             => ['required', 'numeric', 'min:0.1'],
            'unit'                 => ['required', new Enum(TransactionUnit::class)],
            // 'amount'               => ['required', 'numeric', 'min:0'],
            'transaction_date'     => ['required', 'date'],
            'description'          => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'             => 'Tipe transaksi wajib dipilih.',
            'unit_price.required'       => 'Harga satuan wajib diisi.',
            'quantity.required'         => 'Jumlah wajib diisi.',
            'unit.required'             => 'Satuan transaksi wajib dipilih.',
            // 'amount.required'           => 'Nominal transaksi wajib diisi.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'category.required'         => 'Kategori transaksi wajib diisi.',
        ];
    }
}