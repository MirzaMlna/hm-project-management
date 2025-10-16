<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bonus Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

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


                {{-- Form --}}
                @include('worker-bonuses.partials._bonus-input')

            </div>
        </div>
    </div>

    {{-- Script --}}
    @include('worker-bonuses.partials._script')
</x-app-layout>
