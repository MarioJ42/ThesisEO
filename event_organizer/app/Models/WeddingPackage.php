<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi One-to-Many: Satu paket wedding bisa digunakan di banyak event
    public function events()
    {
        return $this->hasMany(Event::class, 'package_id');
    }
}
