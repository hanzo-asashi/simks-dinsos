<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Village;

trait HasWilayah
{
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
}
