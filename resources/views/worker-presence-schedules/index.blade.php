<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            Rentang Waktu Presensi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-xl p-8 border border-gray-100">
                {{-- Alert sukses/gagal --}}
                @if (session('success'))
                    <div id="alert-success"
                        class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div id="alert-error"
                        class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                {{-- Judul --}}
                <div class="mb-6 text-center md:text-left">
                    <p class="text-sm text-gray-500 mt-1">
                        Atur waktu mulai dan berakhir untuk setiap sesi presensi harian.
                    </p>
                </div>

                {{-- Form --}}
                @include('worker-presence-schedules.partials._schedule-input')

            </div>
        </div>
    </div>

    {{-- Auto-hide alert --}}
    <script>
        setTimeout(() => {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');
            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    alert.classList.add('opacity-0');
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</x-app-layout>
