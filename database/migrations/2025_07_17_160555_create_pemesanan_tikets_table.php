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
        Schema::create('pemesanan_tikets', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel customers (wajib)
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade'); // Jika customer dihapus, hapus juga semua pemesanannya

            // Relasi ke tabel users (admin). Nullable karena bisa saja dibuat tanpa login
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // Jika admin dihapus, set ke null agar data tetap aman

            $table->string('kode_pemesanan')->unique(); // Contoh: TKT20250716001

            $table->date('tanggal_pemesanan'); // Tanggal saat pemesanan dibuat
            $table->date('tanggal_kunjungan'); // Kapan tiket akan digunakan

            $table->integer('total_tiket'); // Total semua tiket yang dipesan
            $table->decimal('total_harga', 10, 2); // Total semua harga

            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending'); // Status pesanan

            $table->string('bukti_pembayaran')->nullable(); // File bukti pembayaran (jika ada)

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan__tikets');
    }
};
