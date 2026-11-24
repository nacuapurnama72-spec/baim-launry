<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->foreignId('id_layanan')->constrained('services')->restrictOnDelete();
            $table->decimal('berat_kg', 8, 2);
            $table->integer('total_harga');
            $table->date('tanggal_masuk');
            $table->dateTime('estimasi_selesai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->enum('status', ['Proses', 'Selesai'])->default('Proses');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
