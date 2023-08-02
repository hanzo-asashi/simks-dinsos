<?php
declare(strict_types=1);

use App\Models\JenisBansos;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->uuid('dtks_id')->nullable()->default(Str::orderedUuid());
            $table->string('nik',16);
            $table->string('nokk',16);
            $table->string('nama');
            $table->text('alamat');
            $table->string('no_rumah');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('kode_pos',6);
            $table->string('no_telepon', 14);
            $table->foreignIdFor(JenisBansos::class);
            $table->unsignedTinyInteger('status_kpm')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
