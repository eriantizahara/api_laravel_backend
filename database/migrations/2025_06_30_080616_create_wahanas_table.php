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
        Schema::create('wahanas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_wahana')->unique(); // Kode unik wahana
            $table->string('nama_wahana');          // Nama wahana
            $table->text('deskripsi')->nullable();       // Deskripsi wahana
            $table->decimal('harga_tiket', 10, 2);       // Harga tiket
            $table->string('foto')->nullable();       // Foto wahana
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wahanas');
    }
};
