<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class DailyFeedLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        // Format input angka (ganti koma jadi titik)
        $amountPerFeed = $this->amount_per_feed_g ? (float) str_replace(',', '.', $this->amount_per_feed_g) : null;
        $amountKg = $this->amount_kg ? (float) str_replace(',', '.', $this->amount_kg) : null;
        $frequency = (int) ($this->feeding_frequency ?? 1);

        // Kalkulasi Otomatis di Backend jika salah satu tidak diisi
        if ($amountPerFeed && !$amountKg) {
            $amountKg = ($amountPerFeed * $frequency) / 1000;
        } elseif ($amountKg && !$amountPerFeed && $frequency > 0) {
            $amountPerFeed = ($amountKg * 1000) / $frequency;
        }

        $this->merge([
            'amount_per_feed_g' => $amountPerFeed,
            'amount_kg'         => $amountKg,
            'feeding_frequency' => $frequency,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'          => ['required', 'exists:batches,id'],
            'feed_type_id'      => ['nullable', 'exists:feed_types,id'],
            'feed_date'         => ['required', 'date'],
            'amount_per_feed_g' => ['required_without:amount_kg', 'nullable', 'numeric', 'min:0'],
            'feeding_frequency' => ['required', 'integer', 'min:1'],
            'amount_kg'         => ['required_without:amount_per_feed_g', 'nullable', 'numeric', 'min:0.001'],
            'appetite_response' => ['required', 'in:sangat_baik,baik,kurang,buruk'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ];
    }
}