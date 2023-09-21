<?php

namespace App\Models;

use App\Enums\StatusAktifEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;

class Family extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'dtks_id',
        'family_id',
        'nik',
        'nokk',
        'nama_keluarga',
        'no_telepon',
        'jenis_bansos_id',
        'status_kpm',
    ];

    protected $casts = [
        'status_kpm' => StatusAktifEnum::class,
    ];

    public function jenisBansos(): BelongsToMany
    {
        return $this->belongsToMany(JenisBansos::class);
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(Anggota::class, 'anggota_keluargas');
    }
}
