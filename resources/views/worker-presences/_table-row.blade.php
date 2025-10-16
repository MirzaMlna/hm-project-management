<tr class="border-b hover:bg-gray-50">
    <td class="p-2">{{ $index + 1 }}</td>
    <td class="p-2">{{ $presence->worker->category->category ?? '-' }}</td>
    <td class="p-2 font-medium text-gray-800">{{ $presence->worker->name }}</td>
    <td class="p-2">{{ $presence->worker->code }}</td>

    {{-- Presensi 1 --}}
    <td class="p-2">
        @if ($presence->first_check_in)
            <span class="font-bold text-sky-700 text-lg">
                {{ \Carbon\Carbon::parse($presence->first_check_in)->format('H:i') }}
            </span><br>
            <span class="text-xs text-gray-600">
                {{ $presence->is_work_earlier ? 'Lebih Awal' : 'Tepat Waktu' }}
            </span>
        @else
            <span class="text-gray-400">-</span>
        @endif
    </td>

    {{-- Presensi 2 --}}
    <td class="p-2">
        @if ($presence->second_check_in)
            <span class="font-bold text-green-700 text-lg">
                {{ \Carbon\Carbon::parse($presence->second_check_in)->format('H:i') }}
            </span><br>
            <span class="text-xs text-gray-600">Tepat Waktu</span>
        @else
            <span class="text-gray-400">-</span>
        @endif
    </td>

    {{-- Presensi Pulang --}}
    <td class="p-2">
        @if ($presence->check_out)
            <span class="font-bold text-amber-700 text-lg">
                {{ \Carbon\Carbon::parse($presence->check_out)->format('H:i') }}
            </span><br>
            <span class="text-xs text-gray-600">
                @if ($presence->is_overtime)
                    Lembur
                @elseif($presence->is_work_longer)
                    Pulang Lambat
                @else
                    Tepat Waktu
                @endif
            </span>
        @else
            <span class="text-gray-400">-</span>
        @endif
    </td>

    <td class="p-2 text-center">
        <form action="{{ route('worker-presences.destroy', $presence->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus presensi ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700">
                <i class="bi bi-trash3"></i>
            </button>
        </form>
    </td>
</tr>
