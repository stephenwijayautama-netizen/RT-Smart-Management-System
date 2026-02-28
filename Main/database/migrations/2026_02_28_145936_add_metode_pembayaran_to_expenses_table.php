<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable()->after('status');
            $table->string('payment_id')->nullable()->after('metode_pembayaran');
            $table->string('payment_url')->nullable()->after('payment_id');
            $table->string('payment_status')->default('PENDING')->after('payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'payment_id', 'payment_url', 'payment_status']);
        });
    }
};
