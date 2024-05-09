<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_anggota',
        'uuid_buku',
        'tanggal_pinjam',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Event listener untuk membuat UUID sebelum menyimpan
        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
