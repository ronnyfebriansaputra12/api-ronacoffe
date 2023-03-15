<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable=[
        'pengeluaran',
        'tanggal',
	    'rincian',
        'inventori_id',
        'jumlah',
        'total'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventori_id', 'id');
    }
}
