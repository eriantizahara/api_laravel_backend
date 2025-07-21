<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Pemesanan_Tiket extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan_tikets';

    protected $fillable = [
        'pemesanan_tiket_id',
        'wahana_id',
        'jumlah',
        'harga',
        'subtotal'
    ];

    /**
     * Relasi: Detail pemesanan milik satu pemesanan utama
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan_Tiket::class, 'pemesanan_tiket_id');
    }

    /**
     * Relasi: Setiap detail pemesanan terkait dengan satu wahana
     */
    public function wahana()
    {
        return $this->belongsTo(Wahana::class);
    }
}
