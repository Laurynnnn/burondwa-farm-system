<?php

namespace Modules\GeneralData\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GeneralData\Models\UnitOfMeasure;

class UnitOfMeasureController extends Controller
{
    public function index()
    {
        $units = UnitOfMeasure::orderBy('name')->paginate(15);
        return view('generaldata::units.index', compact('units'));
    }

    public function create()
    {
        return view('generaldata::units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:units_of_measure,name',
            'abbreviation' => 'required|string|max:10|unique:units_of_measure,abbreviation',
            'description' => 'nullable|string|max:255',
        ]);
        UnitOfMeasure::create($validated);
        return redirect()->route('generaldata.units.index')->with('success', 'Unit of measure created.');
    }

    public function edit(UnitOfMeasure $unit)
    {
        return view('generaldata::units.edit', compact('unit'));
    }

    public function update(Request $request, UnitOfMeasure $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:units_of_measure,name,' . $unit->id,
            'abbreviation' => 'required|string|max:10|unique:units_of_measure,abbreviation,' . $unit->id,
            'description' => 'nullable|string|max:255',
        ]);
        $unit->update($validated);
        return redirect()->route('generaldata.units.index')->with('success', 'Unit of measure updated.');
    }

    public function destroy(UnitOfMeasure $unit)
    {
        $unit->delete();
        return redirect()->route('generaldata.units.index')->with('success', 'Unit of measure deleted.');
    }
} 