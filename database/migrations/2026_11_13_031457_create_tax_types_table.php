<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel tax_types. 
     */
    public function up(): void
    {
        Schema::create('tax_types', function (Blueprint $table) {
            $table->id();

            $table->string('tax_code', 30)->unique();
            $table->string('name', 100);

            $table->decimal('rate_percent', 5, 2);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Membatasi tarif pajak simulasi dari 0% sampai 100%.
        DB::statement(
            'ALTER TABLE tax_types
             ADD CONSTRAINT tax_types_rate_percent_check
             CHECK (rate_percent >= 0 AND rate_percent <= 100)'
        );
    }

    /**
     * Menghapus tabel saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_types');
    }
};
