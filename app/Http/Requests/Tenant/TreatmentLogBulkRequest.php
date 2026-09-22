<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentLogBulkRequest extends FormRequest
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
                if (isset($log['dosage_amount'])) {
                    $logs[$index]['dosage_amount'] = ($log['dosage_amount'] !== null && $log['dosage_amount'] !== '') 
                        ? str_replace(',', '.', $log['dosage_amount']) 
                        : null;
                }
            }
        }

        $this->merge(['logs' => $logs]);
    }

    public function rules(): array
    {
        return [
            'treatment_date'      => ['required', 'date'],
            'logs'                => ['required', 'array', 'min:1'],
            'logs.*.batch_id'     => ['required', 'exists:batches,id'],
            'logs.*.product_name' => ['required', 'string', 'max:255'],
            'logs.*.dosage_amount'=> ['required', 'numeric', 'min:0.01'],
            'logs.*.dosage_unit'  => ['required', 'string', 'max:20'],
            'logs.*.purpose'      => ['nullable', 'string', 'max:255'],
            'logs.*.notes'        => ['nullable', 'string', 'max:1000'],
        ];
    }
}