<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 
        'table_tennis_table_id', 
        'booking_date', 
        'duration_minutes', 
        'status', 
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tableTennisTable()
    {
        return $this->belongsTo(TableTennisTable::class);
    }
}