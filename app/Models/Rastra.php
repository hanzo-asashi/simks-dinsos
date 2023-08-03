<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rastra extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'rastra';
//    protected $primaryKey = 'rastra_uuid';
//    protected $keyType = 'uuid';

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
        'bukti_foto' => 'array'
    ];
}
