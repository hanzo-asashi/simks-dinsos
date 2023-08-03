<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBansos extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'warna',
        'short',
        'deskripsi',
    ];

    public function family(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
