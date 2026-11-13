<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel transaksi TaxFlow.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_code', 40)->unique();

            $table->foreignId('merchant_id')
                ->constrained('merchants')
                ->restrictOnDelete();

            $table->foreignId('tax_type_id')
                ->constrained('tax_types')
                ->restrictOnDelete();

            $table->timestamp('transaction_date');

            $table->decimal('base_amount', 18, 2);
            $table->decimal('tax_rate_percent', 5, 2);
            $table->decimal('tax_amount', 18, 2);
            $table->decimal('total_amount', 18, 2);

            $table->string('status', 20)->default('draft');
            $table->text('description')->nullable();

            $table->timestamps();

            // Indeks untuk relasi transaksi.
            $table->index('merchant_id');
            $table->index('tax_type_id');

            // Indeks untuk filter dan laporan.
            $table->index('transaction_date');
            $table->index('status');
        });

        // Menjaga validitas nilai transaksi.
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_amount_check
            CHECK (
                base_amount >= 0
                AND tax_amount >= 0
                AND total_amount >= 0
            )
        ");

        // Tarif simulasi harus berada antara 0% dan 100%.
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_rate_check
            CHECK (
                tax_rate_percent >= 0
                AND tax_rate_percent <= 100
            )
        ");

        // Hanya status yang telah ditentukan yang diperbolehkan.
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_status_check
            CHECK (
                status IN ('draft', 'completed', 'cancelled')
            )
        ");

        // Memastikan jumlah total sesuai dengan komponennya.
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_total_check
            CHECK (total_amount = base_amount + tax_amount)
        ");
    }

    /**
     * Menghapus tabel ketika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
