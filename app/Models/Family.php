<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Family extends Model
{
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
        'status_kpm' => 'boolean',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function jenisBansos(): BelongsToMany
    {
        return $this->belongsToMany(JenisBansos::class);
    }
}
