<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periksa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_daftar_poli');
            $table->dateTime('tgl_periksa')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('catatan')->nullable();
            $table->integer('biaya_periksa');
            $table->timestamps();
            
            $table->foreign('id_daftar_poli')->references('id')->on('daftar_poli')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periksa');
    }
};

