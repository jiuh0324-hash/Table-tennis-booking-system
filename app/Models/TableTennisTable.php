<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableTennisTable extends Model
{
    protected $fillable = ['name', 'description', 'is_available'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availableBookings()
    {
        return $this->bookings()->where('status', 'confirmed');
    }
}