<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class MortalityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'total_weight_kg' => $this->total_weight_kg ? str_replace(',', '.', $this->total_weight_kg) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'        => ['required', 'exists:batches,id'],
            'log_date'        => ['required', 'date'],
            'quantity_pcs'    => ['required', 'integer', 'min:1'],
            'total_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'indication'      => ['nullable', 'string', 'max:255'],
            'action_taken'    => ['nullable', 'string'],
        ];
    }
}