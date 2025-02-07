<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'item_id' => ['required'],
            'total_price' => ['required'],
            'price' => ['required', 'integer'],
            'status' => ['string'],
            'supplier_name' => ['required', 'string'],
            'qty' => ['required', 'integer'],
            'invoice_number' => ['string']
        ];
    }
    
}
