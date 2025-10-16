<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                Presensi Tukang
            </h2>
            <div class="text-end">
                <p class="text-sm font-semibold text-gray-700">
                    <span id="current-time"></span> <br>
                    <span id="current-date"></span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🟦 Jadwal Presensi --}}
            @include('worker-presences.partials._schedule-cards', [
                'worker_presence_schedules' => $worker_presence_schedules,
            ])

            {{-- 🟨 Layout Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Scanner --}}
                @include('worker-presences.partials._scanner', ['notPresentCount' => $notPresentCount])

                {{-- Tabel --}}
                @include('worker-presences.partials._table', ['presences' => $presences])
            </div>
        </div>
    </div>

    {{-- 🟩 Modal Export Excel --}}
    @include('worker-presences.partials._excel-modal')

    {{-- 📜 SCRIPT --}}


    @include('worker-presences.partials._script')
</x-app-layout>
