<?php

namespace App\Http\Controllers;

use App\Models\DevelopmentPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DevelopmentPointController extends Controller
{
    /**
     * Tampilkan daftar titik pembangunan.
     */
    public function index()
    {
        $points = DevelopmentPoint::latest()->paginate(10);
        return view('development-points.index', compact('points'));
    }

    /**
     * Tampilkan form create (kalau dipakai).
     */
    public function create()
    {
        return view('development-points.create');
    }

    /**
     * Simpan titik pembangunan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'development_point' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('development_point');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('development-points', 'public');
        }

        DevelopmentPoint::create($data);

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit (kalau dipakai).
     */
    public function edit(DevelopmentPoint $developmentPoint)
    {
        return view('development-points.edit', compact('developmentPoint'));
    }

    /**
     * Update titik pembangunan.
     */
    public function update(Request $request, DevelopmentPoint $developmentPoint)
    {
        $request->validate([
            'development_point' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('development_point');

        if ($request->hasFile('photo')) {
            // hapus foto lama jika ada
            if ($developmentPoint->photo && Storage::disk('public')->exists($developmentPoint->photo)) {
                Storage::disk('public')->delete($developmentPoint->photo);
            }

            $data['photo'] = $request->file('photo')->store('development-points', 'public');
        }

        $developmentPoint->update($data);

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil diperbarui.');
    }

    /**
     * Hapus titik pembangunan.
     */
    public function destroy(DevelopmentPoint $developmentPoint)
    {
        // hapus foto lama kalau ada
        if ($developmentPoint->photo && Storage::disk('public')->exists($developmentPoint->photo)) {
            Storage::disk('public')->delete($developmentPoint->photo);
        }

        $developmentPoint->delete();

        return redirect()->route('development-points.index')->with('success', 'Titik pembangunan berhasil dihapus.');
    }
}
