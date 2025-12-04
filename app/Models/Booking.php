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
        return $this->belongsTo(TableTennisTable::class, 'table_tennis_table_id');
    }

    public static function hasTimeConflict($tableId, $bookingDate, $durationMinutes, $excludeId = null)
    {
        $startTime = \Carbon\Carbon::parse($bookingDate);
        $endTime = $startTime->copy()->addMinutes($durationMinutes);

        $query = self::where('table_tennis_table_id', $tableId)
            ->where('status', 'confirmed')
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('booking_date', '<', $endTime)
                  ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) > ?', [$startTime]);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
    
    public function getEndTimeAttribute()
    {
        return $this->booking_date->addMinutes($this->duration_minutes);
    }
}