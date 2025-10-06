<?php

namespace App\Http\Controllers;

use App\Models\DevelopmentPoint;
use Illuminate\Http\Request;

class DevelopmentPointController extends Controller
{
    public function index()
    {
        $points = DevelopmentPoint::latest()->paginate(10);
        return view('development-points.index', compact('points'));
    }

    public function create()
    {
        return view('development-points.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'development_point' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DevelopmentPoint::create($request->only('development_point', 'description'));

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil ditambahkan.');
    }

    public function edit(DevelopmentPoint $developmentPoint)
    {
        return view('development-points.edit', compact('developmentPoint'));
    }

    public function update(Request $request, DevelopmentPoint $developmentPoint)
    {
        $request->validate([
            'development_point' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $developmentPoint->update($request->only('development_point', 'description'));

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil diperbarui.');
    }

    public function destroy(DevelopmentPoint $developmentPoint)
    {
        $developmentPoint->delete();

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil dihapus.');
    }
}
