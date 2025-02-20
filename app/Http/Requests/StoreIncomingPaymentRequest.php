<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomingPaymentRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'sale_id' => 'exists:sales,id',
            'invoice_number' => 'required|string|max:50',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:tunai,transfer',
            'bank_account_number' => 'required_if:payment_method,transfer|nullable|string',
            'payment_code' => 'required_if:payment_method,transfer|nullable|string|max:50',
            'pay_amount' => 'required|numeric|min:0',
            'information' => 'nullable|string|max:255',
            'sub_total' => 'required|numeric|min:0',
            'remaining_payment' => 'required|string',
            // total_paid
            // 'tax' => 'required|numeric|min:0',
        ];
    }
}
