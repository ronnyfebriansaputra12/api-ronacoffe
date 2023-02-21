<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kd_barang',
        'nama_barang',
        'jenis_barang',
        'stok',
        'harga',
        'satuan',
    ];
}

