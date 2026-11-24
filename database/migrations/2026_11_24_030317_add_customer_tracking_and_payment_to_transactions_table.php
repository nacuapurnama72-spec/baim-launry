<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('kode_transaksi')->constrained('customers')->nullOnDelete();
            $table->enum('payment_status', ['Lunas', 'Belum Bayar'])->default('Belum Bayar')->after('total_harga');
            $table->string('invoice_number', 50)->nullable()->unique()->after('kode_transaksi');
            $table->text('catatan')->nullable();
        });

        DB::statement("ALTER TABLE transactions MODIFY status VARCHAR(30) NOT NULL DEFAULT 'Diterima'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'payment_status', 'invoice_number', 'catatan']);
        });
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('Proses','Selesai') NOT NULL DEFAULT 'Proses'");
    }
};
