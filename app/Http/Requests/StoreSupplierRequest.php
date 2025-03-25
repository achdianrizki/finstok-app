<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'supplier_code' => ['required', 'string'],
            'name' => ['required', 'string'],
            'npwp' => ['integer', 'nullable'],
            'contact' => ['required', 'string'],
            'discount1' => ['nullable', 'numeric'],
            'discount2' => ['nullable', 'numeric'],
            'phone' => ['nullable', 'string'],
            'fax_nomor' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'status' => ['required', 'boolean'],
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
            'supplier_code.required' => 'Kode pemasok wajib diisi.',
            'name.required' => 'Nama wajib diisi.',
            'contact.required' => 'Kontak wajib diisi.',
            'discount1.numeric' => 'Diskon 1 harus berupa angka.',
            'discount2.numeric' => 'Diskon 2 harus berupa angka.',
            'phone.string' => 'Telepon harus berupa teks.',
            'fax_nomor.string' => 'Nomor faks harus berupa teks.',
            'address.required' => 'Alamat wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'payment_term.integer' => 'Jangka waktu pembayaran harus berupa angka.',
            'status.required' => 'Status wajib diisi.',
            'status.boolean' => 'Status harus berupa nilai benar atau salah.',
        ];
    }
}
