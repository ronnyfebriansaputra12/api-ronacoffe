<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable=[
        'pemasukan',
        'tanggal',

    ];
}
