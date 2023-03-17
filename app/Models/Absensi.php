<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'absensis';

    protected $fillable = ['id','user_id', 'tanggal', 'masuk','pulang','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



