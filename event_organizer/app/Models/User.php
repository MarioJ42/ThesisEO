<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function clientEvents()
    {
        return $this->hasMany(Event::class, 'client_id');
    }

    public function plEvents()
    {
        return $this->hasMany(Event::class, 'pl_id');
    }

    public function assignedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_crew')->withPivot('jobdesk')->withTimestamps();
    }
}
