<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class DailyFeedLogBulkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $frequency = (int) ($this->feeding_frequency ?? 1);
        $logs = $this->logs ?? [];

        // Format angka desimal koma -> titik & kalkulasi otomatis per baris
        foreach ($logs as $index => $log) {
            if (isset($log['enabled']) && $log['enabled'] == '1') {
                $amountPerFeed = !empty($log['amount_per_feed_g']) ? (float) str_replace(',', '.', $log['amount_per_feed_g']) : null;
                $amountKg = !empty($log['amount_kg']) ? (float) str_replace(',', '.', $log['amount_kg']) : null;

                if ($amountPerFeed && !$amountKg) {
                    $amountKg = ($amountPerFeed * $frequency) / 1000;
                } elseif ($amountKg && !$amountPerFeed && $frequency > 0) {
                    $amountPerFeed = ($amountKg * 1000) / $frequency;
                }

                $logs[$index]['amount_per_feed_g'] = $amountPerFeed;
                $logs[$index]['amount_kg'] = $amountKg;
            }
        }

        $this->merge([
            'feeding_frequency' => $frequency,
            'logs' => $logs,
        ]);
    }

    public function rules(): array
    {
        return [
            'feed_date'                    => ['required', 'date'],
            'feed_type_id'                 => ['nullable', 'exists:feed_types,id'],
            'feeding_frequency'            => ['required', 'integer', 'min:1'],
            'logs'                         => ['required', 'array', 'min:1'],
            'logs.*.batch_id'              => ['required', 'exists:batches,id'],
            'logs.*.enabled'               => ['nullable', 'in:1'],
            'logs.*.amount_per_feed_g'     => ['nullable', 'numeric', 'min:0'],
            'logs.*.amount_kg'             => ['nullable', 'required_if:logs.*.enabled,1', 'numeric', 'min:0.001'],
            'logs.*.appetite_response'     => ['required_if:logs.*.enabled,1', 'in:sangat_baik,baik,kurang,buruk'],
            'logs.*.notes'                 => ['nullable', 'string', 'max:500'],
        ];
    }
}