<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class DailyFeedLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount_kg' => $this->amount_kg ? str_replace(',', '.', $this->amount_kg) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'          => ['required', 'exists:batches,id'],
            'feed_type_id'      => ['nullable', 'exists:feed_types,id'],
            'feed_date'         => ['required', 'date'],
            'feed_time'         => ['required', 'date_format:H:i'],
            'amount_kg'         => ['required', 'numeric', 'min:0.01'],
            'appetite_response' => ['required', 'in:sangat_baik,baik,kurang,buruk'],
            'notes'             => ['nullable', 'string'],
        ];
    }
}