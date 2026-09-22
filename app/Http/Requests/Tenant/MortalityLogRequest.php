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
            'total_weight_g' => $this->total_weight_g ? str_replace(',', '.', $this->total_weight_g) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'        => ['required', 'exists:batches,id'],
            'log_date'        => ['required', 'date'],
            'quantity_pcs'    => ['required', 'integer', 'min:1'],
            'total_weight_g'  => ['nullable', 'numeric', 'min:0'],
            'indication'      => ['nullable', 'string', 'max:255'],
            'action_taken'    => ['nullable', 'string'],
        ];
    }
}