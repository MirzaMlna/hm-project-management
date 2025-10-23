<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkerBonus;

class WorkerBonusController extends Controller
{
    public function index()
    {
        $workerBonus = WorkerBonus::first();
        return view('worker-bonuses.index', compact('workerBonus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_longer' => 'required|numeric|min:0',
        ]);

        $bonus = WorkerBonus::first();

        if ($bonus) {
            $bonus->update([
                'work_longer' => $request->work_longer,
            ]);
        } else {
            WorkerBonus::create([
                'work_longer' => $request->work_longer,
            ]);
        }

        return redirect()->route('worker-bonuses.index')
            ->with('success', 'Pengaturan bonus berhasil disimpan.');
    }
}
