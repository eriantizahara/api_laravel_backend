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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('namacustomer');           // Nama lengkap customer
            $table->string('username')->unique();     // Username unik
            $table->string('password');               // Password terenkripsi
            $table->string('email')->unique();        // Email unik
            $table->string('nohp')->nullable();      // Nomor HP
            $table->text('alamat')->nullable();       // Alamat lengkap
            $table->timestamps();                     // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
