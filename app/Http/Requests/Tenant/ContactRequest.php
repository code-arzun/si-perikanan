<?php
namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Jika mode UPDATE (route memiliki parameter 'contact'), pastikan kontak milik tenant user yang sedang login
        // if ($this->route('contact')) {
        //     $contact = $this->route('contact');
        //     return auth()->check() && $contact->tenant_id === auth()->user()->tenant_id;
        // }

        // return auth()->check();

        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:supplier,buyer,both',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes'   => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama supplier/buyer wajib diisi.',
            'type.required' => 'Tipe kontak wajib dipilih.',
            'type.in'       => 'Pilihan tipe kontak tidak valid.',
            'email.email'   => 'Format email tidak valid.',
        ];
    }
}