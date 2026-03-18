<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'address',
        'instagram',
        'is_active',
    ];

    public function categories()
    {
        return $this->belongsToMany(VendorCategory::class, 'category_vendor', 'vendor_id', 'category_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_vendor')
            ->withPivot('vendor_contact_id', 'vendor_package_id', 'deal_price', 'status')
            ->withTimestamps();
    }

    public function contacts()
    {
        return $this->hasMany(VendorContact::class);
    }

    public function packages()
    {
        return $this->hasMany(VendorPackage::class);
    }

    public function portfolios()
    {
        return $this->hasMany(VendorPortfolio::class);
    }
}
