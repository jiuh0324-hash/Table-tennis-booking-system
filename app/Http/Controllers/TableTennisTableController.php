<?php
namespace App\Http\Controllers;
use App\Models\TableTennisTable;
use Illuminate\Http\Request;

class TableTennisTableController extends Controller
{
    public function index()
    {
        $tables = TableTennisTable::with('availableBookings')->get();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        TableTennisTable::create($validated);
        return redirect()->route('tables.index')
            ->with('success', 'Table created successfully!');
    }

    public function edit(TableTennisTable $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, TableTennisTable $table)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        $table->update($validated);
        return redirect()->route('tables.index')
            ->with('success', 'Table updated successfully!');
    }

    public function destroy(TableTennisTable $table)
    {
        $table->delete();
        return redirect()->route('tables.index')
            ->with('success', 'Table deleted successfully!');
    }
}