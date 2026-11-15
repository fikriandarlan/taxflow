<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMerchantRequest extends FormRequest
{
    /**
     * Hanya pengguna terautentikasi yang diizinkan.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalisasi kode merchant sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('merchant_code'))) {
            $this->merge([
                'merchant_code' => strtoupper(
                    trim($this->input('merchant_code'))
                ),
            ]);
        }
    }

    /**
     * Aturan validasi untuk memperbarui merchant.
     */
    public function rules(): array
    {
        $merchant = $this->route('merchant');

                $allowedAddresses = collect(
            config('java_cities', [])
        )
            ->flatten()
            ->values()
            ->all();

        // Izinkan alamat lama hanya untuk Merchant
        // yang saat ini sedang diedit.
        if (
            $merchant !== null
            && is_string($merchant->address)
            && $merchant->address !== ''
        ) {
            $allowedAddresses[] = $merchant->address;
        }

        return [
            'merchant_code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9_-]+$/',
                Rule::unique('merchants', 'merchant_code')
                    ->ignore($merchant),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
                Rule::in($allowedAddresses),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Pesan validasi berbahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'merchant_code.required' =>
                'Kode merchant wajib diisi.',

            'merchant_code.unique' =>
                'Kode merchant sudah digunakan.',

            'merchant_code.regex' =>
                'Kode hanya boleh berisi huruf, angka, strip, dan underscore.',

            'name.required' =>
                'Nama merchant wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'is_active.required' =>
                'Status merchant wajib dipilih.',

            'is_active.boolean' =>
                'Status merchant tidak valid.',

            'address.in' =>
                'Pilih Kota/Kabupaten yang tersedia atau pertahankan alamat lama.',
        ];
    }
}
