<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxTypeRequest extends FormRequest
{
    /**
     * Hanya pengguna yang sudah login.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalisasi data sebelum divalidasi.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('tax_code'))) {
            $this->merge([
                'tax_code' => strtoupper(
                    trim($this->input('tax_code'))
                ),
            ]);
        }
    }

    /**
     * Aturan validasi jenis pajak.
     */
    public function rules(): array
    {
        return [
            'tax_code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9_-]+$/',
                'unique:tax_types,tax_code',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'rate_percent' => [
                'required',
                'numeric',
                'between:0,100',
                'decimal:0,2',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'tax_code.required' =>
                'Kode pajak wajib diisi.',

            'tax_code.unique' =>
                'Kode pajak sudah digunakan.',

            'tax_code.regex' =>
                'Kode hanya boleh berisi huruf, angka, strip, dan underscore.',

            'name.required' =>
                'Nama jenis pajak wajib diisi.',

            'rate_percent.required' =>
                'Tarif pajak wajib diisi.',

            'rate_percent.numeric' =>
                'Tarif pajak harus berupa angka.',

            'rate_percent.between' =>
                'Tarif pajak harus berada antara 0 sampai 100 persen.',

            'rate_percent.decimal' =>
                'Tarif pajak maksimal dua angka di belakang desimal.',

            'is_active.required' =>
                'Status wajib dipilih.',

            'is_active.boolean' =>
                'Status tidak valid.',
        ];
    }
}
