<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class WaterQualityLogBulkRequest extends FormRequest
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
                if (isset($log['ph'])) {
                    $logs[$index]['ph'] = $log['ph'] !== null && $log['ph'] !== '' ? str_replace(',', '.', $log['ph']) : null;
                }
                if (isset($log['do_mg_l'])) {
                    $logs[$index]['do_mg_l'] = $log['do_mg_l'] !== null && $log['do_mg_l'] !== '' ? str_replace(',', '.', $log['do_mg_l']) : null;
                }
                if (isset($log['temperature_c'])) {
                    $logs[$index]['temperature_c'] = $log['temperature_c'] !== null && $log['temperature_c'] !== '' ? str_replace(',', '.', $log['temperature_c']) : null;
                }
                if (isset($log['transparency_cm'])) {
                    $logs[$index]['transparency_cm'] = $log['transparency_cm'] !== null && $log['transparency_cm'] !== '' ? str_replace(',', '.', $log['transparency_cm']) : null;
                }
            }
        }

        $this->merge(['logs' => $logs]);
    }

    public function rules(): array
    {
        return [
            'check_date'             => ['required', 'date'],
            'check_time_session'     => ['required', 'in:pagi,siang,sore,malam'],
            'logs'                   => ['required', 'array', 'min:1'],
            'logs.*.batch_id'        => ['required', 'exists:batches,id'],
            'logs.*.ph'              => ['nullable', 'numeric', 'min:0', 'max:14'],
            'logs.*.do_mg_l'         => ['nullable', 'numeric', 'min:0'],
            'logs.*.temperature_c'   => ['nullable', 'numeric', 'min:0'],
            'logs.*.transparency_cm' => ['nullable', 'numeric', 'min:0'],
            'logs.*.water_color'     => ['nullable', 'string', 'max:255'],
            'logs.*.notes'           => ['nullable', 'string', 'max:1000'],
        ];
    }
}