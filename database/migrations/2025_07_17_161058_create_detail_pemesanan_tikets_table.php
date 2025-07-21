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
        Schema::create('detail_pemesanan_tikets', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel pemesanan_tikets
            $table->foreignId('pemesanan_tiket_id')
                ->constrained('pemesanan_tikets')
                ->onDelete('cascade'); // Jika pemesanan dihapus, detail juga ikut dihapus

            // Relasi ke wahana
            $table->foreignId('wahana_id')
                ->constrained('wahanas')
                ->onDelete('cascade'); // Jika wahana dihapus, hapus detailnya juga

            $table->integer('jumlah'); // Jumlah tiket untuk wahana ini
            $table->decimal('harga', 10, 2); // Harga per tiket saat itu
            $table->decimal('subtotal', 10, 2); // jumlah x harga

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__pemesanan__tikets');
    }
};
