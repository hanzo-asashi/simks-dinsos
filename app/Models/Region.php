<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Province;
use KodePandai\Indonesia\Models\Village;

class Region extends Model
{
    protected $fillable = [
        'nama_wilayah',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'latitude',
        'longitude',
        'house_id',
        'status',
        'location'
    ];

    protected $appends = [
        'location'
    ];

    public function house(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id', 'id');
    }

    public function kab(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class, 'kabupaten', 'code');
    }

    public function kec(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan', 'code');
    }

    public function kel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Village::class, 'kelurahan', 'code');
    }

    /**
     * Returns the 'latitude' and 'longitude' attributes as the computed 'location' attribute,
     * as a standard Google Maps style Point array with 'lat' and 'lng' attributes.
     *
     * Used by the Filament Google Maps package.
     *
     * Requires the 'location' attribute be included in this model's $fillable array.
     *
     * @return array
     */

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
