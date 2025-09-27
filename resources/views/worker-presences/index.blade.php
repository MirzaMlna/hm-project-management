<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Presensi') }}
        </h2>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                @php
                    $cards = [
                        [
                            'label' => 'Presensi Pertama',
                            'color' => 'blue',
                            'start' => $worker_presence_schedules->first_check_in_start,
                            'end' => $worker_presence_schedules->first_check_in_end,
                        ],
                        [
                            'label' => 'Presensi Kedua',
                            'color' => 'green',
                            'start' => $worker_presence_schedules->second_check_in_start,
                            'end' => $worker_presence_schedules->second_check_in_end,
                        ],
                        [
                            'label' => 'Presensi Pulang',
                            'color' => 'amber',
                            'start' => $worker_presence_schedules->check_out_start,
                            'end' => $worker_presence_schedules->check_out_end,
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-{{ $card['color'] }}-500">
                        <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($card['start'])->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($card['end'])->format('H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>


</x-app-layout>
