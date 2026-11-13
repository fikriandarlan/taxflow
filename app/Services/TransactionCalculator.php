<?php

namespace App\Services;

use InvalidArgumentException;

class TransactionCalculator
{
    /**
     * Batas maksimum NUMERIC(18,2).
     */
    private const MAX_AMOUNT = '9999999999999999.99';

    /**
     * Menghitung nominal pajak simulasi.
     *
     * Input dan output menggunakan string desimal.
     *
     * @return array{
     *     base_amount: string,
     *     tax_rate_percent: string,
     *     tax_amount: string,
     *     total_amount: string
     * }
     */
    public function calculate(
        string $baseAmount,
        string $taxRatePercent
    ): array {
        // Format nilai harus sesuai skala database.
        if (! preg_match(
            '/^\d{1,16}(\.\d{1,2})?$/D',
            $baseAmount
        )) {
            throw new InvalidArgumentException(
                'Format nominal dasar tidak valid.'
            );
        }

        if (! preg_match(
            '/^\d{1,3}(\.\d{1,2})?$/D',
            $taxRatePercent
        )) {
            throw new InvalidArgumentException(
                'Format tarif pajak tidak valid.'
            );
        }

        // Pastikan nilai berada dalam rentang aman.
        if (
            bccomp($baseAmount, self::MAX_AMOUNT, 2) > 0
        ) {
            throw new InvalidArgumentException(
                'Nominal dasar melampaui batas database.'
            );
        }

        if (
            bccomp($taxRatePercent, '100', 2) > 0
        ) {
            throw new InvalidArgumentException(
                'Tarif pajak tidak boleh melebihi 100%.'
            );
        }

        // Normalisasi dua angka desimal.
        $base = bcadd($baseAmount, '0', 2);
        $rate = bcadd($taxRatePercent, '0', 2);

        // Pajak = nominal dasar × tarif / 100.
        // Pertahankan empat desimal sementara.
        $rawTax = bcdiv(
            bcmul($base, $rate, 4),
            '100',
            4
        );

        // Pembulatan HALF UP ke dua desimal.
        // Berlaku karena seluruh nominal non-negatif.
        $tax = bcadd($rawTax, '0.005', 2);

        // Total = nominal dasar + nominal pajak.
        $total = bcadd($base, $tax, 2);

        // Total juga harus muat dalam NUMERIC(18,2).
        if (
            bccomp($total, self::MAX_AMOUNT, 2) > 0
        ) {
            throw new InvalidArgumentException(
                'Total transaksi melampaui batas database.'
            );
        }

        return [
            'base_amount' => $base,
            'tax_rate_percent' => $rate,
            'tax_amount' => $tax,
            'total_amount' => $total,
        ];
    }
}
