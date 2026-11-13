<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel merchants.
     */
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();

            $table->string('merchant_code', 30)->unique();
            $table->string('name', 150);

            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Menghapus tabel saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
