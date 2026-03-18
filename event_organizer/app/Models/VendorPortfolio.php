<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'title',
        'image_path',
        'description',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
