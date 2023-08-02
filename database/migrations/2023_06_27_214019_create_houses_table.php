<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('houses', static function (Blueprint $table) {
            $table->id();
            $table->uuid('house_uuid')->nullable()->default(Str::orderedUuid());
            $table->string('qrcode')->nullable()->default(Str::random(8));
            $table->string('nama')->default('');
            $table->text('alamat')->default('');
            $table->integer('no_rumah')->nullable()->default(1);
            $table->string('kodepos')->nullable()->default(setting('default.kodepos'));
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kabupaten')->default(setting('default.kodekab'));
            $table->string('kecamatan')->default('');
            $table->string('kelurahan')->default('');
            $table->string('lattitude')->nullable()->default('');
            $table->string('longitude')->nullable()->default('');
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
