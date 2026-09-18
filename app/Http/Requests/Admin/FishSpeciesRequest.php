<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FishSpeciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_superadmin;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'latin_name'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ];
    }
}