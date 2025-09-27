<?php

namespace App\Http\Controllers;

use App\Models\WorkerPresenceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkerPresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $worker_presence_schedules = WorkerPresenceSchedule::first();

        $selectedDate = request('date', Carbon::today()->toDateString());

        return view('worker-presences.index', compact('worker_presence_schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
