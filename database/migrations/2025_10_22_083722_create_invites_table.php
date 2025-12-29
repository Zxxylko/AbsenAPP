<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah tabel sudah ada
        if (!Schema::hasTable('invites')) {
            Schema::create('invites', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->boolean('is_used')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invites')) {
            Schema::dropIfExists('invites');
        }
    }
};
