<?php

namespace App\Models;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PengambilanBarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventori_id',
        'jumlah',
        'keterangan'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventori_id', 'id');
    }
}
