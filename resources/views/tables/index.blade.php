@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Tables</h2>
                <a href="{{ route('tables.create') }}" class="btn btn-primary">Add New Table</a>
            </div>
            
            @if($tables->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tables as $table)
                            <tr>
                                <td>{{ $table->id }}</td>
                                <td>{{ $table->name }}</td>
                                <td>{{ $table->description }}</td>
                                <td>
                                    <span class="badge bg-{{ $table->is_available ? 'success' : 'danger' }}">
                                        {{ $table->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tables.edit', $table) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('tables.destroy', $table) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No tables found. <a href="{{ route('tables.create') }}">Add the first table!</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection