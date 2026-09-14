<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class SamplingLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'avg_weight_g'          => $this->avg_weight_g ? str_replace(',', '.', $this->avg_weight_g) : null,
            'avg_length_cm'         => $this->avg_length_cm ? str_replace(',', '.', $this->avg_length_cm) : null,
            'estimated_biomass_kg'  => $this->estimated_biomass_kg ? str_replace(',', '.', $this->estimated_biomass_kg) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'             => ['required', 'exists:batches,id'],
            'sampling_date'        => ['required', 'date'],
            'sample_count_pcs'     => ['required', 'integer', 'min:1'],
            'avg_weight_g'         => ['required', 'numeric', 'min:0.01'],
            'avg_length_cm'        => ['nullable', 'numeric', 'min:0'],
            'estimated_biomass_kg' => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string'],
        ];
    }
}