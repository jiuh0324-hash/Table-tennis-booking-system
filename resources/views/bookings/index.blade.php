@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>All Bookings</h2>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">New Booking</a>
            </div>
            
            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                    <div class="col-md-6 mb-3">
                        <div class="card booking-card">
                            <div class="card-body">
                                <h5 class="card-title">Table: {{ $booking->tableTennisTable->name }}</h5>
                                <p class="card-text">
                                    <strong>User:</strong> {{ $booking->user->name }}<br>
                                    <strong>Date:</strong> {{ $booking->booking_date->format('M j, Y H:i') }}<br>
                                    <strong>Duration:</strong> {{ $booking->duration_minutes }} minutes<br>
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                                
                                @auth
                                    @if(Auth::id() === $booking->user_id || Auth::user()->isAdmin())
                                    <div class="btn-group">
                                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    No bookings found. <a href="{{ route('bookings.create') }}">Create your first booking!</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection