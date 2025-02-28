<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:10', 'unique:items,code'],
            'purchase_price' => ['required', 'integer'],
            'unit' => ['required', 'string'],
            'stock' => ['integer'],
            'description' => ['nullable', 'string'],
            'suppliers' => ['required', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
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
            'code.required' => 'Kode wajib diisi.',
            'code.unique' => 'Kode sudah digunakan.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'suppliers.required' => 'Pemasok wajib diisi.',
            'suppliers.*.exists' => 'Pemasok tidak valid.',
            'category_id.required' => 'Kategori wajib diisi.',
            'category_id.exists' => 'Kategori tidak valid.',
            'warehouse_id.required' => 'Gudang wajib diisi.',
            'warehouse_id.exists' => 'Gudang tidak valid.',
        ];
    }
}
