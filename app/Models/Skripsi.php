<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Skripsi extends Model
{
    use HasFactory;

    protected $table = 'skripsis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'judul',
        'abstrak',
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
