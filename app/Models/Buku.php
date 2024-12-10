<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'uuid_kategori',
        'judul',
        'sinopsis',
        'pengarang',
        'tahun_terbit',
        'penerbit',
        'lokasi',
        'stok',
        'foto',
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
