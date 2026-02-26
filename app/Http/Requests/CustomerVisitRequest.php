<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerVisitRequest extends FormRequest
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
            $others = array_filter(array_map('trim', explode(',', $otherContacts)));
            $contacts = array_merge($contacts, $others);
        }

        $this->merge([
            'contact_persons' => array_values(array_unique($contacts)),
        ]);
    }

    public function rules(): array
    {
        return [
            'visit_date' => 'required|date',
            'visit_reason' => 'required|string',
            'visit_notes' => 'nullable|string',
            'contact_persons' => 'nullable|array',
            'customer_product_id' => 'nullable|exists:customer_products,id',
            'barcode' => 'nullable|string|max:100',
            'lot_no' => 'nullable|string|max:100',
            'complaint_id' => 'nullable|exists:complaints,id',
            'findings' => 'required|string',
            'result' => 'required|string',
            'visit_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:10240'
        ];
    }
}