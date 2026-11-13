<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateInitialUser extends Command
{
    protected $signature = 'taxflow:create-user';

    protected $description = 'Membuat pengguna awal TaxFlow';

    public function handle(): int
    {
        $name = trim($this->ask('Nama pengguna') ?? '');
        $email = strtolower(trim($this->ask('Email') ?? ''));

        $password = $this->secret('Password (input tersembunyi)');
        $confirmation = $this->secret('Ulangi password');

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirmation,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(12)->mixedCase()->numbers()->symbols(),
                ],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Akun pengguna TaxFlow berhasil dibuat.');

        return self::SUCCESS;
    }
}
