<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thl_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();
            $table->string('nik')->nullable(); // dari No KTP
            $table->string('departemen')->nullable(); // ambil dari attendances
            $table->string('kota')->nullable(); // dari TEMPAT
            $table->date('tanggal_lahir')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thl_data');
    }
};
