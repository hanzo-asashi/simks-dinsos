<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'house_uuid',
        'qrcode',
        'alamat',
        'lattitude',
        'longitude',
        'rt',
        'rw',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'status',
        'keterangan',
        'no_rumah',
        'kodepos',
    ];

    protected $casts = [
        'house_uuid' => 'string',
        'qrcode' => 'string',
    ];

    protected $appends = [
        'location'
    ];

     public function kab(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kabupaten', 'code');
    }

    public function kec(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan', 'code');
    }

    public function kel(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'kelurahan', 'code');
    }

    public function getLocationAttribute(): array
    {
        return [
            'lat' => (float)$this->latitude,
            'lng' => (float)$this->longitude,
        ];
    }

    /**
     * Takes a Google style Point array of 'lat' and 'lng' values and assigns them to the
     * 'latitude' and 'longitude' attributes on this model.
     *
     * Used by the Filament Google Maps package.
     *
     * Requires the 'location' attribute be included in this model's $fillable array.
     *
     * @param ?array $location
     * @return void
     */
    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location)) {
            $this->attributes['latitude'] = $location['lat'];
            $this->attributes['longitude'] = $location['lng'];
            unset($this->attributes['location']);
        }
    }

    /**
     * Get the lat and lng attribute/field names used on this table
     *
     * Used by the Filament Google Maps package.
     *
     * @return string[]
     */
    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    /**
     * Get the name of the computed location attribute
     *
     * Used by the Filament Google Maps package.
     *
     * @return string
     */
    public static function getComputedLocation(): string
    {
        return 'location';
    }


}
