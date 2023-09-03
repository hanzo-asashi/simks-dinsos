<?php

use App\Models\JenisBansos;
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
        if (! Schema::hasTable('anggotas')) {
            Schema::create('anggotas', function (Blueprint $table) {
                $table->id();
                $table->string('dtks_id_anggota')->nullable();
                $table->string('nik_anggota', 16);
                $table->string('nokk_anggota', 16);
                $table->string('nama_anggota');
                $table->string('no_telp_anggota');
                $table->string('alamat_anggota');
                $table->string('kecamatan_anggota');
                $table->string('kelurahan_anggota');
                $table->string('kodepos_anggota');
                $table->foreignIdFor(JenisBansos::class)->constrained('jenis_bantuan')->cascadeOnDelete();
                $table->enum('status_keluarga_anggota', [
                    'suami' => 'Suami',
                    'istri' => 'Istri',
                    'anak' => 'Anak',
                ]);
                $table->boolean('status_anggota')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
