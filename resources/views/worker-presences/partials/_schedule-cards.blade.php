<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    @php
        $cards = [
            [
                'label' => 'Presensi 1',
                'color' => 'blue',
                'start' => $worker_presence_schedules->first_check_in_start,
                'end' => $worker_presence_schedules->first_check_in_end,
            ],
            [
                'label' => 'Presensi 2',
                'color' => 'green',
                'start' => $worker_presence_schedules->second_check_in_start,
                'end' => $worker_presence_schedules->second_check_in_end,
            ],
        ];
    @endphp
    @foreach ($cards as $card)
        <div class="bg-white border-l-4 border-{{ $card['color'] }}-500 shadow-sm rounded-xl p-5">
            <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ \Carbon\Carbon::parse($card['start'])->format('H:i') }} -
                {{ \Carbon\Carbon::parse($card['end'])->format('H:i') }}
            </p>
        </div>
    @endforeach
</div>
