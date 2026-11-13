<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /**
     * Nama tabel PostgreSQL.
     */
    protected $table = 'transactions';

    /**
     * Kolom yang dapat diisi melalui Eloquent.
     *
     * Field perhitungan dan status harus diisi
     * oleh backend, bukan dipercaya dari browser.
     */
    protected $fillable = [
        'transaction_code',
        'merchant_id',
        'tax_type_id',
        'transaction_date',
        'base_amount',
        'tax_rate_percent',
        'tax_amount',
        'total_amount',
        'status',
        'description',
    ];

    /**
     * Konversi tipe atribut.
     */
    protected function casts(): array
    {
        return [
            'merchant_id' => 'integer',
            'tax_type_id' => 'integer',
            'transaction_date' => 'datetime',
            'base_amount' => 'decimal:2',
            'tax_rate_percent' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * Setiap transaksi dimiliki satu Merchant.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(
            Merchant::class,
            'merchant_id'
        );
    }

    /**
     * Setiap transaksi menggunakan satu TaxType.
     */
    public function taxType(): BelongsTo
    {
        return $this->belongsTo(
            TaxType::class,
            'tax_type_id'
        );
    }
}
