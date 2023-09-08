<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Family */
class FamilyResource extends JsonResource
{
    public bool $preserveKeys = false;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dtks_id' => $this->dtks_id,
            'nik' => $this->nik,
            'nokk' => $this->nokk,
            'nama_keluarga' => $this->nama_keluarga,
            'no_telepon' => $this->no_telepon,
            'jenis_bansos_id' => $this->jenis_bansos_id,
            'status_kpm' => $this->status_kpm,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'anggota_count' => $this->anggota_count,
            'audits_count' => $this->audits_count,
            'jenis_bansos_count' => $this->jenis_bansos_count,

            //            'family' => new FamilyResource($this->whenLoaded('family')), //
        ];
    }
}
