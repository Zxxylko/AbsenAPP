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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');  // sesuai Excel
            $table->string('nama_karyawan');
            $table->string('departemen')->nullable();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('status_masuk')->nullable();
            $table->string('status_pulang')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('lembur')->nullable();
        });



    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
