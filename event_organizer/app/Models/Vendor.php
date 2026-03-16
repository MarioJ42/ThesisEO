<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
        return $this->hasMany(VendorContact::class, 'vendor_id');
    }
}
