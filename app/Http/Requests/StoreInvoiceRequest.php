<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'number' => 'required|digits:9|unique:invoices,number',
            'value' => 'required|numeric|min:0.01',
            'issuance_date' => 'required|date|before_or_equal:today',
            'sender_cnpj' => 'required|regex:/^\d{14}$/',
            'sender_name' => 'required|string|max:100',
            'transporter_cnpj' => 'required|regex:/^\d{14}$/',
            'transporter_name' => 'required|string|max:100',
        ];
    }
    public function messages()
    {
        return [
            'number.digits' => 'The number field must be exactly 9 digits.',
            'value.min' => 'The value must be greater than 0.',
            'issuance_date.before_or_equal' => 'The issuance date cannot be a future date.',
            'sender_cnpj.regex' => 'Invalid sender CNPJ format.',
            'sender_name.max' => 'Sender name should not exceed 100 characters.',
            'transporter_cnpj.regex' => 'Invalid transporter CNPJ format.',
            'transporter_name.max' => 'Transporter name should not exceed 100 characters.',
        ];
    }
}
