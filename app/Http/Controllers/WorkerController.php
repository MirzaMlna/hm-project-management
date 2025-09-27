<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::where('is_active', true)->paginate(10);
        $totalWorkers = Worker::count();
        $activeWorkers = Worker::where('is_active', true)->count();
        $totalDailySalary = Worker::where('is_active', true)->sum('daily_salary');

        return view('workers.index', compact('workers', 'totalWorkers', 'activeWorkers', 'totalDailySalary'));
    }

    public function inactive()
    {
        $workers = Worker::where('is_active', false)->paginate(10);
        return view('workers.inactive', compact('workers'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'code' => 'required|string|max:10|unique:workers',
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'nullable|boolean',
            'note'         => 'nullable|string',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Generate kode otomatis
        $count = Worker::count() + 1;
        $kode = 'TKG' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Pastikan kode unik (optional)
        while (Worker::where('code', $kode)->exists()) {
            $count++;
            $kode = 'TKG' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $validated['code'] = $kode;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('workers', 'public');
        }

        Worker::create($validated);

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil ditambahkan.');
    }


    public function show(Worker $worker)
    {
        $qrCode = (new \Hashids\Hashids('', 40))->encode($worker->id);
        return view('workers.show', compact('worker', 'qrCode'));
    }

    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            // 'code'         => 'required|string|max:10|unique:workers,code,' . $worker->id,
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'nullable|boolean',
            'note'         => 'nullable|string',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($worker->photo && Storage::disk('public')->exists($worker->photo)) {
                Storage::disk('public')->delete($worker->photo);
            }
            // Simpan foto baru
            $validated['photo'] = $request->file('photo')->store('workers', 'public');
        }

        $worker->update($validated);

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil diperbarui.');
    }

    public function destroy(Worker $worker)
    {
        // Hapus foto lama jika ada
        if ($worker->photo && Storage::disk('public')->exists($worker->photo)) {
            Storage::disk('public')->delete($worker->photo);
        }

        $worker->delete();

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil dihapus.');
    }
    public function deactivate(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $worker->update([
            'is_active' => false,
            'note' => $validated['note'],
        ]);

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil dinonaktifkan.');
    }
    public function activate(Request $request, Worker $worker)
    {
        $worker->update([
            'is_active' => true,
            'note' => null, // reset note saat aktifkan kembali
        ]);

        return redirect()->route('workers.inactive')->with('success', 'Tukang berhasil diaktifkan kembali.');
    }
}
