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
        // Tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('kodeuser')->unique();         // Kode unik user
            $table->string('name');                       // Nama user
            $table->string('email')->unique();            // Email unik
            $table->string('password');                   // Password
            $table->string('nohp')->nullable();           // Nomor HP (optional)
            $table->text('alamat')->nullable();           // Alamat lengkap (optional)
            $table->enum('status', ['admin', 'customer']); // Status user (admin/customer)
            $table->rememberToken();                      // Token remember me
            $table->timestamps();                         // created_at & updated_at
        });

        // Tabel password reset
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();           // Email untuk reset
            $table->string('token');                      // Token reset
            $table->timestamp('created_at')->nullable();  // Waktu dibuat
        });

        // Tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();              // ID session
            $table->foreignId('user_id')->nullable()->index(); // ID user (optional)
            $table->string('ip_address', 45)->nullable(); // Alamat IP
            $table->text('user_agent')->nullable();       // Info perangkat
            $table->longText('payload');                  // Data session
            $table->integer('last_activity')->index();    // Waktu aktivitas terakhir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
