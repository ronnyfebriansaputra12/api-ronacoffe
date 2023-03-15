<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PengeluaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'inventori_id' => $this->inventori_id,
            'jumlah' => $this->jumlah,
            'tanggal' => $this->tanggal,
            'rincian' => $this->rincian,
            'pengeluaran' => $this->pengeluaran,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'nama_barang' => $this->inventory->nama_barang,
        ];
    }
}
