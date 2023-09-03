<?php

use App\Models\Anggota;
use App\Models\Family;
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
        if (! Schema::hasTable('anggota_keluargas')) {
            Schema::create('anggota_keluargas', function (Blueprint $table) {
                $table->foreignIdFor(Family::class)
                    ->constrained('families')
                    ->cascadeOnDelete();

                $table->foreignIdFor(Anggota::class)
                    ->constrained('anggotas')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_keluargas');
    }
};
