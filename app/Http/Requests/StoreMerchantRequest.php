<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreMerchantRequest extends FormRequest
    {
        /**
         * Menentukan apakah request diizinkan.
         */
        public function authorize(): bool
        {
            return $this->user() !== null;
        }

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
         * Validasi data merchant.
         */
        public function rules(): array
        {
            return [
                'merchant_code' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^[A-Za-z0-9_-]+$/',
                    'unique:merchants,merchant_code',
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
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],
            ];
        }

        /**
         * Pesan validasi dalam Bahasa Indonesia.
         */
        public function messages(): array
        {
            return [
                'merchant_code.required' => 'Kode merchant wajib diisi.',
                'merchant_code.unique' => 'Kode merchant sudah digunakan.',
                'merchant_code.regex' =>
                'Kode hanya boleh berisi huruf, angka, strip, dan underscore.',
                'name.required' => 'Nama merchant wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'is_active.required' => 'Status merchant wajib dipilih.',
                'is_active.boolean' => 'Status merchant tidak valid.',
            ];
        }
    }
