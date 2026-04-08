<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'vendor_category_id',
        'session',
        'role_detail',
        'is_included'
    ];

    public function category()
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function package()
    {
        return $this->belongsTo(WeddingPackage::class, 'package_id');
    }
}
