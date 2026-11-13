<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Hanya pengguna terautentikasi.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalisasi kode sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('transaction_code'))) {
            $this->merge([
                'transaction_code' => strtoupper(
                    trim($this->input('transaction_code'))
                ),
            ]);
        }
    }

    /**
     * Aturan validasi input Create Transaction.
     */
    public function rules(): array
    {
        return [
            'transaction_code' => [
                'required',
                'string',
                'max:40',
                'regex:/^[A-Za-z0-9_-]+$/',
                Rule::unique(
                    'transactions',
                    'transaction_code'
                ),
            ],

            'merchant_id' => [
                'required',
                'integer',
                Rule::exists('merchants', 'id')
                    ->where('is_active', true),
            ],

            'tax_type_id' => [
                'required',
                'integer',
                Rule::exists('tax_types', 'id')
                    ->where('is_active', true),
            ],

            'transaction_date' => [
                'required',
                'date_format:Y-m-d\TH:i',
            ],

            'base_amount' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
                'regex:/^\d{1,16}(\.\d{1,2})?$/',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi.
     */
    public function messages(): array
    {
        return [
            'transaction_code.required' =>
                'Kode transaksi wajib diisi.',

            'transaction_code.unique' =>
                'Kode transaksi sudah digunakan.',

            'transaction_code.regex' =>
                'Kode hanya boleh berisi huruf, angka, strip, dan underscore.',

            'merchant_id.required' =>
                'Merchant wajib dipilih.',

            'merchant_id.exists' =>
                'Merchant tidak ditemukan atau tidak aktif.',

            'tax_type_id.required' =>
                'Jenis pajak wajib dipilih.',

            'tax_type_id.exists' =>
                'Jenis pajak tidak ditemukan atau tidak aktif.',

            'transaction_date.required' =>
                'Tanggal transaksi wajib diisi.',

            'transaction_date.date_format' =>
                'Format tanggal transaksi tidak valid.',

            'base_amount.required' =>
                'Nominal dasar wajib diisi.',

            'base_amount.numeric' =>
                'Nominal dasar harus berupa angka.',

            'base_amount.min' =>
                'Nominal dasar tidak boleh negatif.',

            'base_amount.decimal' =>
                'Nominal dasar maksimal dua angka desimal.',

            'base_amount.regex' =>
                'Nominal dasar terlalu besar atau format tidak valid.',

            'description.max' =>
                'Keterangan maksimal 2000 karakter.',
        ];
    }
}
