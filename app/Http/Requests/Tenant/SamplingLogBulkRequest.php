<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class SamplingLogBulkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $logs = $this->input('logs', []);

        if (is_array($logs)) {
            foreach ($logs as $index => $log) {
                if (isset($log['avg_weight_g'])) {
                    $logs[$index]['avg_weight_g'] = ($log['avg_weight_g'] !== null && $log['avg_weight_g'] !== '') 
                        ? str_replace(',', '.', $log['avg_weight_g']) 
                        : null;
                }
                if (isset($log['avg_length_cm'])) {
                    $logs[$index]['avg_length_cm'] = ($log['avg_length_cm'] !== null && $log['avg_length_cm'] !== '') 
                        ? str_replace(',', '.', $log['avg_length_cm']) 
                        : null;
                }
                if (isset($log['estimated_biomass_kg'])) {
                    $logs[$index]['estimated_biomass_kg'] = ($log['estimated_biomass_kg'] !== null && $log['estimated_biomass_kg'] !== '') 
                        ? str_replace(',', '.', $log['estimated_biomass_kg']) 
                        : null;
                }
            }
        }

        $this->merge(['logs' => $logs]);
    }

    public function rules(): array
    {
        return [
            'sampling_date'               => ['required', 'date'],
            'logs'                        => ['required', 'array', 'min:1'],
            'logs.*.batch_id'             => ['required', 'exists:batches,id'],
            'logs.*.sample_count_pcs'     => ['required', 'integer', 'min:1'],
            'logs.*.avg_weight_g'         => ['required', 'numeric', 'min:0.01'],
            'logs.*.avg_length_cm'        => ['nullable', 'numeric', 'min:0'],
            'logs.*.estimated_biomass_kg' => ['nullable', 'numeric', 'min:0'],
            'logs.*.notes'                => ['nullable', 'string', 'max:1000'],
        ];
    }
}