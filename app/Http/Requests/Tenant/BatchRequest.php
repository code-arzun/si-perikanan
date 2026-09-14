<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pond_id'                => ['required', 'exists:ponds,id'],
            'fish_species_id'        => ['required', 'exists:fish_species,id'],
            'batch_code'             => ['required', 'string', 'max:50'],
            'start_date'             => ['required', 'date'],
            'estimated_harvest_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'initial_seed_count'     => ['required', 'integer', 'min:1'],
            'initial_avg_weight_g'   => ['required', 'numeric', 'min:0.01'],
            'seed_price_per_unit'    => ['nullable', 'numeric', 'min:0'],
            'target_fcr'             => ['nullable', 'numeric', 'min:0.1'],
            'target_survival_rate'   => ['nullable', 'numeric', 'min:1', 'max:100'],
            'notes'                  => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'initial_avg_weight_g' => $this->initial_avg_weight_g ? str_replace(',', '.', $this->initial_avg_weight_g) : null,
            'seed_price_per_unit'  => $this->seed_price_per_unit ? str_replace(',', '.', $this->seed_price_per_unit) : 0,
        ]);
    }
}