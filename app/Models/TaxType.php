<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxType extends Model
{
    /**
     * Tabel PostgreSQL yang digunakan.
     */
    protected $table = 'tax_types';

    /**
     * Kolom yang dapat diisi melalui Eloquent.
     */
    protected $fillable = [
        'tax_code',
        'name',
        'rate_percent',
        'is_active',
    ];

    /**
     * Konversi tipe atribut.
     */
    protected function casts(): array
    {
        return [
            'rate_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Satu jenis pajak dapat digunakan
     * oleh banyak transaksi.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'tax_type_id'
        );
    }
}
