<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar' 
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && \Storage::disk('public')->exists('avatars/' . $this->avatar)) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        return asset('images/default-avatar.png');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getBookingStats()
    {
        return [
            'total' => $this->bookings()->count(),
            'confirmed' => $this->bookings()->where('status', 'confirmed')->count(),
            'pending' => $this->bookings()->where('status', 'pending')->count(),
            'cancelled' => $this->bookings()->where('status', 'cancelled')->count(),
        ];
    }
}