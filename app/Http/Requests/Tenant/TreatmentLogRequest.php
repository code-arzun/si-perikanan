<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'dosage_amount' => $this->dosage_amount ? str_replace(',', '.', $this->dosage_amount) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'       => ['required', 'exists:batches,id'],
            'treatment_date' => ['required', 'date'],
            'product_name'   => ['required', 'string', 'max:255'],
            'dosage_amount'  => ['required', 'numeric', 'min:0.01'],
            'dosage_unit'    => ['required', 'string', 'max:20'],
            'purpose'        => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}