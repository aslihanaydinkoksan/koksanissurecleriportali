<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // İzin kontrolünü buraya bırakıyoruz (ileride Role/Permission bağlanabilir)
    }

    public function rules(): array
    {
        // Eğer güncelleme ise (URL'den customer modelini al), değilse null
        $customerId = $this->route('customer') ? $this->route('customer')->id : null;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customerId)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',

            // Dinamik İletişim Kişileri Validasyonu
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.title' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
        ];

        // Güncelleme işlemine (PUT/PATCH) özel ek kurallar
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['contacts.*.id'] = 'nullable|integer';
            $rules['contacts.*.delete'] = 'nullable|boolean';
        }

        return $rules;
    }
}