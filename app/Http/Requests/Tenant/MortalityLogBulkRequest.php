<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class MortalityLogBulkRequest extends FormRequest
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
                if (isset($log['total_weight_g'])) {
                    $logs[$index]['total_weight_g'] = ($log['total_weight_g'] !== null && $log['total_weight_g'] !== '') 
                        ? str_replace(',', '.', $log['total_weight_g']) 
                        : null;
                }
            }
        }

        $this->merge(['logs' => $logs]);
    }

    public function rules(): array
    {
        return [
            'log_date'               => ['required', 'date'],
            'logs'                   => ['required', 'array', 'min:1'],
            'logs.*.batch_id'        => ['required', 'exists:batches,id'],
            'logs.*.quantity_pcs'    => ['required', 'integer', 'min:1'],
            'logs.*.total_weight_g'  => ['nullable', 'numeric', 'min:0'],
            'logs.*.indication'      => ['nullable', 'string', 'max:255'],
            'logs.*.action_taken'    => ['nullable', 'string', 'max:1000'],
        ];
    }
}