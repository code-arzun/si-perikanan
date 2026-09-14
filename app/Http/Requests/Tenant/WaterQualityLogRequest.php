<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class WaterQualityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ph'              => $this->ph ? str_replace(',', '.', $this->ph) : null,
            'do_mg_l'         => $this->do_mg_l ? str_replace(',', '.', $this->do_mg_l) : null,
            'temperature_c'   => $this->temperature_c ? str_replace(',', '.', $this->temperature_c) : null,
            'salinity_ppt'    => $this->salinity_ppt ? str_replace(',', '.', $this->salinity_ppt) : null,
            'transparency_cm' => $this->transparency_cm ? str_replace(',', '.', $this->transparency_cm) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'batch_id'           => ['required', 'exists:batches,id'],
            'check_date'         => ['required', 'date'],
            'check_time_session' => ['required', 'in:pagi,siang,sore,malam'],
            'ph'                 => ['nullable', 'numeric', 'min:0', 'max:14'],
            'do_mg_l'            => ['nullable', 'numeric', 'min:0'],
            'temperature_c'      => ['nullable', 'numeric', 'min:0'],
            'salinity_ppt'       => ['nullable', 'numeric', 'min:0'],
            'transparency_cm'    => ['nullable', 'numeric', 'min:0'],
            'water_color'        => ['nullable', 'string', 'max:255'],
            'notes'              => ['nullable', 'string'],
        ];
    }
}