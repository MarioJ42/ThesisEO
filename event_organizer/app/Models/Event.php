<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function pl()
    {
        return $this->belongsTo(User::class, 'pl_id');
    }

    public function package()
    {
        return $this->belongsTo(WeddingPackage::class, 'package_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'event_vendor')
            ->withPivot('vendor_contact_id', 'vendor_package_id', 'deal_price', 'status', 'is_included', 'vendor_category_id')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function crews()
    {
        return $this->belongsToMany(User::class, 'event_crew')->withPivot('jobdesk')->withTimestamps();
    }
}
