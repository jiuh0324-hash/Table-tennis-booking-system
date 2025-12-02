@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Booking</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="table_tennis_table_id" class="form-label">Select Table</label>
                            <select name="table_tennis_table_id" id="table_tennis_table_id" class="form-select" required>
                                <option value="">Choose a table...</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ old('table_tennis_table_id') == $table->id ? 'selected' : '' }}>
                                        {{ $table->name }} - {{ $table->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('table_tennis_table_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Booking Date & Time</label>
                            <input type="datetime-local" name="booking_date" id="booking_date" 
                                   class="form-control" value="{{ old('booking_date') }}" required>
                            @error('booking_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                            <select name="duration_minutes" id="duration_minutes" class="form-select" required>
                                <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="60" {{ old('duration_minutes') == 60 ? 'selected' : '' }}>60 minutes</option>
                                <option value="90" {{ old('duration_minutes') == 90 ? 'selected' : '' }}>90 minutes</option>
                                <option value="120" {{ old('duration_minutes') == 120 ? 'selected' : '' }}>120 minutes</option>
                            </select>
                            @error('duration_minutes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Booking</button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection