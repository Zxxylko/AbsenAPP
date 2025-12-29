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
        Schema::create('data_absensi_thl', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('departemen');
            $table->integer('hadir')->default(0);
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpha')->default(0);
            $table->integer('lembur')->default(0);
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_absensi_thl');
    }
};
