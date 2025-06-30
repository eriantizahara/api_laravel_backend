<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wahana extends Model
{
    use HasFactory;

    protected $table = 'wahanas';

    protected $fillable = [
        'kode_wahana',
        'nama_wahana',
        'deskripsi',
        'harga_tiket',
        'foto',
    ];
}
