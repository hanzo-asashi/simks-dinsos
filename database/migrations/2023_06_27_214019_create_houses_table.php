<?php

declare(strict_types=1);

use App\Models\Family;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Family::class)->constrained()->cascadeOnUpdate();
            $table->text('alamat')->nullable();
            $table->string('kodepos', 7)->nullable()->default(config('default.kodepos'));
            $table->string('rt_rw', 15)->nullable();
            $table->string('kabupaten', 50)->nullable()->default(config('default.kodekab'));
            $table->string('kecamatan', 50)->nullable();
            $table->string('kelurahan', 50)->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->json('foto_rumah')->nullable();
            $table->tinyInteger('status')->nullable()->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
