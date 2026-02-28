<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('house_id')->constrained()->cascadeOnDelete();
            $table->foreignId('occupant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')
                ->constrained('expense_categories')
                ->cascadeOnDelete();

            $table->decimal('jumlah', 12, 2);

            $table->enum('status', ['BELUM_BAYAR', 'SUDAH_BAYAR'])->default('BELUM_BAYAR');

            $table->date('tanggal_pembayaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
