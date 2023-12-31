<?php

declare(strict_types=1);

use App\Models\Family;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rastra', function (Blueprint $table) {
            $table->id();
            $table->uuid('rastra_uuid')->default(Str::orderedUuid());
            $table->string('dtks_id')->nullable();
            $table->foreignId(Family::class)
                ->constrained('families')
                ->cascadeOnDelete();
            $table->string('nik');
            $table->string('nokk')->nullable();
            $table->string('nama_penerima');
            $table->text('alamat');
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->dateTime('tanggal_terima')->nullable();
            $table->string('qrcode')->nullable();
            $table->json('bukti_foto');
            $table->string('lokasi')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('status_rastra', ['Mampu', 'Belum Mampu'])->nullable();
            $table->boolean('status_dtks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rastra');
    }
};
