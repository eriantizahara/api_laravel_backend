<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan_Tiket extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_tikets';

    protected $fillable = [
        'customer_id',
        'user_id',
        'kode_pemesanan',
        'tanggal_pemesanan',
        'tanggal_kunjungan',
        'total_tiket',
        'total_harga',
        'status',
        'bukti_pembayaran'
    ];

    /**
     * Relasi: Pemesanan dimiliki oleh 1 customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi: Pemesanan dibuat oleh 1 user/admin (bisa null)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu pemesanan memiliki banyak detail pemesanan (wahana)
     */
    public function detailPemesanan()
    {
        return $this->hasMany(Detail_Pemesanan_Tiket::class, 'pemesanan_tiket_id');
    }
}
