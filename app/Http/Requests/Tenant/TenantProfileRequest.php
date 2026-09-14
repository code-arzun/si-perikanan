<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TenantProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'tenant_type'    => ['required', 'in:individual,company'],
            'phone_or_email' => ['required', 'string', 'max:255'],
        ];
    }
}