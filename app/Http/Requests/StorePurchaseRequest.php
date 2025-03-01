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
            'item_id' => ['required', 'array'],
            'item_id.*' => ['required', 'exists:items,id'],
            'total_price' => ['required', 'numeric'],
            'price' => ['required', 'integer'],
            'status' => ['nullable', 'string'],
            'supplier_name' => ['required', 'string'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'integer'],
            'purchase_date' => ['required', 'date'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Item harus dipilih.',
            'item_id.*.exists' => 'Item yang dipilih tidak valid.',
            'total_price.required' => 'Total harga harus diisi.',
            'total_price.numeric' => 'Total harga harus berupa angka.',
            'price.required' => 'Harga harus diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'status.string' => 'Status harus berupa teks.',
            'supplier_name.required' => 'Nama supplier harus diisi.',
            'supplier_name.string' => 'Nama supplier harus berupa teks.',
            'qty.required' => 'Jumlah barang harus diisi.',
            'qty.*.integer' => 'Jumlah barang harus berupa angka.',
            'purchase_date.required' => 'Tanggal pembelian harus diisi.',
        ];
    }
}
