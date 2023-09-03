<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jenis_bansos')) {
            Schema::create('jenis_bansos', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 30);
                $table->string('warna')->nullable()->default('primary');
                $table->string('short')->nullable();
                $table->string('deskripsi')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('status_bansos');
    }
};
