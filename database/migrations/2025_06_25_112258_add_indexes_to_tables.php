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
        Schema::table('pasien', function (Blueprint $table) {
            $table->index('no_ktp', 'idx_pasien_no_ktp');
            $table->index('no_rm', 'idx_pasien_no_rm');
        });

        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->index('created_at', 'idx_daftar_poli_tanggal');
        });

        Schema::table('periksa', function (Blueprint $table) {
            $table->index('tgl_periksa', 'idx_periksa_tanggal');
        });

        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->index(['id_dokter', 'hari'], 'idx_jadwal_dokter_hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropIndex('idx_pasien_no_ktp');
            $table->dropIndex('idx_pasien_no_rm');
        });

        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->dropIndex('idx_daftar_poli_tanggal');
        });

        Schema::table('periksa', function (Blueprint $table) {
            $table->dropIndex('idx_periksa_tanggal');
        });

        Schema::table('jadwal_periksa', function (Blueprint $table) {
            $table->dropIndex('idx_jadwal_dokter_hari');
        });
    }
};
