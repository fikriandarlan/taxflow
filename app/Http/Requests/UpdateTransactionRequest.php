<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $transaction = $this->route('transaction');

        return [
            'transaction_code' => [
                'required',
                'string',
                'max:40',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique(
                    'transactions',
                    'transaction_code'
                )->ignore($transaction->id),
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
                'regex:/^\d{1,16}(?:\.\d{1,2})?$/',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_code.unique' =>
                'Kode transaksi sudah digunakan.',

            'transaction_code.regex' =>
                'Kode hanya boleh menggunakan huruf kapital, angka, strip, dan underscore.',

            'merchant_id.exists' =>
                'Merchant tidak ditemukan atau tidak aktif.',

            'tax_type_id.exists' =>
                'Jenis pajak tidak ditemukan atau tidak aktif.',

            'transaction_date.date_format' =>
                'Format tanggal transaksi tidak sesuai.',

            'base_amount.regex' =>
                'Nominal maksimal 16 digit utama dan 2 digit desimal.',

            'base_amount.decimal' =>
                'Nominal maksimal 2 digit desimal.',
        ];
    }
}
