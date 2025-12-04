<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TableTennisTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'tableTennisTable'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $tables = TableTennisTable::where('is_available', true)->get();
        return view('bookings.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_tennis_table_id' => 'required|exists:table_tennis_tables,id',
            'booking_date' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:120',
            'notes' => 'nullable|string|max:500'
        ]);

        $newStart = Carbon::parse($validated['booking_date']);
        $newEnd = $newStart->copy()->addMinutes($validated['duration_minutes']);
        
        $overlappingBooking = Booking::where('table_tennis_table_id', $validated['table_tennis_table_id'])
            ->where('status', 'confirmed')
            ->where(function($query) use ($newStart, $newEnd) {
                $query->where(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '<=', $newStart)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) > ?', [$newStart]);
                })->orWhere(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '<', $newEnd)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) > ?', [$newEnd]);
                })->orWhere(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '>=', $newStart)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) <= ?', [$newEnd]);
                });
            })
            ->exists();

        if ($overlappingBooking) {
            return back()
                ->withInput()
                ->with('error', 'This time slot is already booked! Please choose another time.')
                ->withErrors(['booking_date' => 'Time slot conflict detected.']);
        }

        $booking = new Booking($validated);
        $booking->user_id = Auth::id();
        $booking->status = 'confirmed'; 
        $booking->save();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    public function edit(Booking $booking)
    {
        $tables = TableTennisTable::where('is_available', true)->get();
        return view('bookings.edit', compact('booking', 'tables'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'table_tennis_table_id' => 'required|exists:table_tennis_tables,id',
            'booking_date' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:120',
            'notes' => 'nullable|string|max:500'
        ]);

        $newStart = Carbon::parse($validated['booking_date']);
        $newEnd = $newStart->copy()->addMinutes($validated['duration_minutes']);
        
        $overlappingBooking = Booking::where('table_tennis_table_id', $validated['table_tennis_table_id'])
            ->where('status', 'confirmed')
            ->where('id', '!=', $booking->id) 
            ->where(function($query) use ($newStart, $newEnd) {
                $query->where(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '<=', $newStart)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) > ?', [$newStart]);
                })->orWhere(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '<', $newEnd)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) > ?', [$newEnd]);
                })->orWhere(function($q) use ($newStart, $newEnd) {
                    $q->where('booking_date', '>=', $newStart)
                      ->whereRaw('DATE_ADD(booking_date, INTERVAL duration_minutes MINUTE) <= ?', [$newEnd]);
                });
            })
            ->exists();

        if ($overlappingBooking) {
            return back()
                ->withInput()
                ->with('error', 'This time slot is already booked! Please choose another time.')
                ->withErrors(['booking_date' => 'Time slot conflict detected.']);
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }

    public function myBookings()
    {
        $bookings = Auth::user()->bookings()
            ->with('tableTennisTable')
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('bookings.my-bookings', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        return redirect()->route('bookings.index')
            ->with('success', 'Booking confirmed successfully!');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully!');
    }
}