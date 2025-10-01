<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkerBonus;

class WorkerBonusController extends Controller
{
    /**
     * Tampilkan form pengaturan bonus tukang
     */
    public function index()
    {
        $bonus = WorkerBonus::first();
        return view('worker-bonuses.index', compact('bonus'));
    }

    /**
     * Simpan / Update bonus (data tunggal)
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_earlier'  => 'required|numeric|min:0',
            'work_longer'   => 'required|numeric|min:0',
            'overtime'      => 'required|numeric|min:0',
        ]);

        // ambil data pertama, kalau null buat baru
        $bonus = WorkerBonus::first();

        if ($bonus) {
            $bonus->update([
                'work_earlier' => $request->work_earlier,
                'work_longer'  => $request->work_longer,
                'overtime'     => $request->overtime,
            ]);
        } else {
            WorkerBonus::create([
                'work_earlier' => $request->work_earlier,
                'work_longer'  => $request->work_longer,
                'overtime'     => $request->overtime,
            ]);
        }

        return redirect()->route('worker-bonuses.index')->with('success', 'Pengaturan bonus berhasil disimpan.');
    }
}
