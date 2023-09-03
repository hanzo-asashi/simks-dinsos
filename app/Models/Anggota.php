<?php

namespace App\Models;

use App\Concerns\HasWilayah;
use App\Enums\StatusKeluargaAnggotaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anggota extends Model
{
    use HasWilayah;

    protected $table = 'anggotas';

    protected $fillable = [
        'dtks_id_anggota',
        'nik_anggota',
        'nokk_anggota',
        'nama_anggota',
        'no_telp_anggota',
        'alamat_anggota',
        'kecamatan_anggota',
        'kelurahan_anggota',
        'kodepos_anggota',
        'status_keluarga_anggota',
        'status_anggota',
    ];

    protected $casts = [
        'status_anggota' => 'boolean',
        'status_keluarga_anggota' => StatusKeluargaAnggotaEnum::class,
    ];

    public function jenisBantuan(): BelongsToMany
    {
        return $this->belongsToMany(JenisBansos::class);
    }

    public function keluarga(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'anggota_keluargas');
    }
}
