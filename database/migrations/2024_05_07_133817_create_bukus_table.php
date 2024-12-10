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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('uuid_kategori');
            $table->string('judul');
            $table->text('sinopsis')->nullable();
            $table->string('pengarang')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('stok')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
