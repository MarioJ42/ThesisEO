<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'min_price',
        'max_price',
        'details',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
