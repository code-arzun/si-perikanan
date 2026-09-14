<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class HarvestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'weight_kg'          => $this->weight_kg ? str_replace(',', '.', $this->weight_kg) : null,
            'price_per_kg'       => $this->price_per_kg ? str_replace(',', '.', $this->price_per_kg) : null,
            'avg_fish_weight_g'  => $this->avg_fish_weight_g ? str_replace(',', '.', $this->avg_fish_weight_g) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'          => ['required', 'exists:batches,id'],
            'harvest_date'      => ['required', 'date'],
            'harvest_type'      => ['required', 'in:partial,total'],
            'weight_kg'         => ['required', 'numeric', 'min:0.1'],
            'total_pcs'         => ['nullable', 'integer', 'min:1'],
            'fish_size'         => ['nullable', 'integer', 'min:1'], // Ekor per Kg (cth: size 8)
            'price_per_kg'      => ['nullable', 'numeric', 'min:0'],
            'buyer_name'        => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string'],
        ];
    }
}