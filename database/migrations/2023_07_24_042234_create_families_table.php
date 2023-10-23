<?php

declare(strict_types=1);

use App\Models\JenisBansos;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->uuid('dtks_id')->nullable()->default(Str::orderedUuid());
            $table->string('nik', 16);
            $table->string('nokk', 16);
            $table->string('nama_keluarga' . 150);
            $table->string('no_telepon', 14);
            $table->foreignIdFor(JenisBansos::class);
            $table->unsignedTinyInteger('status_kpm')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
