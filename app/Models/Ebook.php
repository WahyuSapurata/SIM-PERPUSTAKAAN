<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Ebook extends Model
{
    use HasFactory;

    protected $table = 'ebooks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'judul',
        'sinopsis',
        'pengarang',
        'foto',
        'file',
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
