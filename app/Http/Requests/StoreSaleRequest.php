<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'distributor_id' => ['required', 'integer', 'exists:distributors,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'], 
            'qty_sold' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'in:cash,credit'], 
            'payment_status' => ['required', 'string', 'in:lunas,belum lunas'], 
            'discount' => ['nullable', 'numeric', 'min:0'], 
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'remaining_payment' => ['nullable', 'numeric', 'min:0'], 
            'total_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
