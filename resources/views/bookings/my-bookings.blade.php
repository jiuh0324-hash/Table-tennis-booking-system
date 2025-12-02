@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Bookings</h2>
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
                                    <strong>Date:</strong> {{ $booking->booking_date->format('M j, Y H:i') }}<br>
                                    <strong>Duration:</strong> {{ $booking->duration_minutes }} minutes<br>
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if($booking->notes)
                                        <br><strong>Notes:</strong> {{ $booking->notes }}
                                    @endif
                                </p>
                                
                                <div class="btn-group">
                                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    You have no bookings. <a href="{{ route('bookings.create') }}">Create your first booking!</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection