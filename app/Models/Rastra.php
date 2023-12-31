<?php

namespace App\Models;

use App\Concerns\HasWilayah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Rastra extends Model implements Auditable
{
    use HasWilayah;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'rastra';

    protected $fillable = [
        'dtks_id',
        'rastra_uuid',
        'nik',
        'nokk',
        'nama_penerima',
        'alamat',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'tanggal_terima',
        'qrcode',
        'bukti_foto',
        'lokasi',
        'latitude',
        'longitude',
        'status_rastra',
        'status_dtks',
    ];

    protected $casts = [
        'tanggal_terima' => 'datetime',
        'status_dtks' => 'boolean',
        'dtks_id' => 'string',
        'bukti_foto' => 'array',
    ];

    //    public function kab(): BelongsTo
    //    {
    //        return $this->belongsTo(City::class, 'kabupaten', 'code');
    //    }
    //
    //    public function kec(): BelongsTo
    //    {
    //        return $this->belongsTo(District::class, 'kecamatan', 'code');
    //    }
    //
    //    public function kel(): BelongsTo
    //    {
    //        return $this->belongsTo(Village::class, 'kelurahan', 'code');
    //    }
}
