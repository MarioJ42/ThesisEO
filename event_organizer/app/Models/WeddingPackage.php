<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_price',
        'eo_fee',
        'is_active'
    ];

    public function templates()
    {
        return $this->hasMany(PackageTemplate::class, 'package_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'package_vendor_pivot', 'package_id', 'vendor_id')
            ->withPivot('vendor_category_id')
            ->withTimestamps();
    }
}
