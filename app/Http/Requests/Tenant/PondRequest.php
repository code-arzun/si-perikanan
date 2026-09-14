<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PondRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'length'   => $this->length ? str_replace(',', '.', $this->length) : null,
            'width'    => $this->width ? str_replace(',', '.', $this->width) : null,
            'diameter' => $this->diameter ? str_replace(',', '.', $this->diameter) : null,
            'depth'    => $this->depth ? str_replace(',', '.', $this->depth) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'code'         => ['required', 'string', 'max:50'],
            'name'         => ['required', 'string', 'max:255'],
            'shape'        => ['required', 'in:bulat,persegi,persegi panjang,lainnya'],
            'length'       => ['nullable', 'numeric', 'min:0'],
            'width'        => ['nullable', 'numeric', 'min:0'],
            'diameter'     => ['nullable', 'numeric', 'min:0'],
            'depth'        => ['required', 'numeric', 'min:0.1'],
            'pond_type_id' => ['nullable', 'exists:pond_types,id'],
            'status'       => ['required', 'in:kosong,aktif,perawatan'],
        ];
    }
}