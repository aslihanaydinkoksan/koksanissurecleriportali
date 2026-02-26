<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $contacts = $this->input('contact_persons', []);
        $otherContacts = $this->input('other_contact_persons');

        if (!empty($otherContacts)) {
            // Virgülle ayrılmış isimleri diziye çevir ve boşlukları temizle
            $others = array_filter(array_map('trim', explode(',', $otherContacts)));
            $contacts = array_merge($contacts, $others);
        }

        // Aynı isim iki kez yazıldıysa tekilleştir ve request'e geri bas
        $this->merge([
            'contact_persons' => array_values(array_unique($contacts)),
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'contact_persons' => 'nullable|array',
            'contact_persons.*' => 'string',
            'description' => 'required|string',
            'activity_date' => 'required|date',
        ];
    }
}