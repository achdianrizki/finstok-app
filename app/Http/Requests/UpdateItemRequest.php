<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'stock' => ['integer'],
            'description' => ['nullable', 'string'],
            'suppliers' => ['required', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
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
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'purchase_price.integer' => 'Harga beli harus berupa angka.',
            'unit.required' => 'Satuan wajib diisi.',
            'unit.string' => 'Satuan harus berupa string.',
            'stock.integer' => 'Stok harus berupa angka.',
            'description.string' => 'Deskripsi harus berupa string.',
            'suppliers.required' => 'Pemasok wajib diisi! Minimal 1 Pemasok',
            'suppliers.array' => 'Pemasok harus berupa array.',
            'suppliers.*.exists' => 'Pemasok yang dipilih tidak valid.',
            'category_id.required' => 'Kategori wajib diisi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
