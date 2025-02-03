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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'payment_method' => ['required'],
            'buyer' => ['required', 'string'],
            'item_id' => ['required'],
            'distributor_id' => ['required'],
            'diskon' => ['required'],
            'amount' => ['required', 'integer'],
            'total_price' => ['required', 'integer'],
        ];
    }
}
