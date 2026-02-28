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
        Schema::create('occupants', function (Blueprint $table) {
            $table->id();

            $table->string('nama_lengkap');
            $table->foreignId('house_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('user_id');

            $table->string('foto_ktp')->nullable();
            $table->enum('status_penghuni', ['TETAP', 'KONTRAK']);
            $table->string('nomor_telepon');
            $table->enum('status_menikah', ['SUDAH', 'BELUM']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupants');
    }
};
