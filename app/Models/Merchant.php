<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'merchant_code',
    'name',
    'email',
    'phone',
    'address',
    'is_active',
])]
class Merchant extends Model
{
    /**
     * Konversi tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Satu merchant memiliki banyak transaksi.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'merchant_id'
        );
    }
}
