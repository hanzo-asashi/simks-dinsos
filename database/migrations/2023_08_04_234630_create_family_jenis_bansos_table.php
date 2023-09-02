<?php

use App\Models\Family;
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
        Schema::create('family_jenis_bansos', function (Blueprint $table) {
            $table->foreignIdFor(Family::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(JenisBansos::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_jenis_bansos');
    }
};
