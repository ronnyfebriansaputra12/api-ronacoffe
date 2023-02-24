<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanBarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventori_id',
        'jumlah',
        'keterangan'
    ];

    public function inventori()
    {
        return $this->belongsTo(Inventori::class);
    }
}
