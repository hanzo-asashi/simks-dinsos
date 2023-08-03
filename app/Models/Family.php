<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'dtks_id',
        'nik',
        'nokk',
        'nama',
        'alamat',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'no_rumah',
        'kode_pos',
        'no_telepon',
        'jenis_bansos_id',
        'status_kpm',
    ];

    public function jenisBansos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(JenisBansos::class);
    }
}
