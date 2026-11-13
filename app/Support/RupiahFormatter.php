<?php

namespace App\Support;

use InvalidArgumentException;

class RupiahFormatter
{
    /**
     * Mengubah string desimal standar menjadi format Indonesia.
     *
     * Contoh:
     * 150.00   => 150,00
     * 150000.00 => 150.000,00
     * 8250.50  => 8.250,50
     *
     * Tidak menggunakan float untuk menjaga presisi.
     */
    public static function format(
        string|int $amount
    ): string {
        $value = (string) $amount;

        // Hanya menerima angka desimal standar.
        if (! preg_match(
            '/^-?\d+(?:\.\d{1,2})?$/D',
            $value
        )) {
            throw new InvalidArgumentException(
                'Format nominal tidak valid.'
            );
        }

        $negative = str_starts_with($value, '-');

        if ($negative) {
            $value = substr($value, 1);
        }

        $parts = explode('.', $value, 2);

        $integer = ltrim($parts[0], '0');

        if ($integer === '') {
            $integer = '0';
        }

        $decimal = str_pad(
            $parts[1] ?? '',
            2,
            '0',
            STR_PAD_RIGHT
        );

        $integer = preg_replace(
            '/\B(?=(\d{3})+(?!\d))/',
            '.',
            $integer
        );

        return ($negative ? '-' : '')
            . $integer
            . ','
            . $decimal;
    }
}
