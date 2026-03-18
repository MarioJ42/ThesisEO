<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description'
    ];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'category_vendor', 'category_id', 'vendor_id');
    }
}
