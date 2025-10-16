<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Barang Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 🔹 Alert sukses / error --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 rounded bg-red-100 text-red-800 shadow-sm transition-opacity duration-500">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- 🔹 Header & Tombol --}}
                <div class="flex flex-wrap justify-between items-center px-6 mt-6 gap-3">
                    {{-- Filter Bulan --}}
                    <form method="GET" action="{{ route('item-outs.index') }}" class="flex items-center gap-2">
                        <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                            Filter Bulan:
                        </label>
                        <input type="month" id="month" name="month"
                            value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                            class="border-gray-300 rounded-md p-2 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition"
                            onchange="this.form.submit()">
                    </form>

                    {{-- Tombol Tambah --}}
                    <div class="flex items-center gap-2">
                        <x-primary-button class="w-10 h-10 flex items-center justify-center"
                            onclick="toggleCreateModal()" title="Tambah Barang Keluar">
                            <i class="bi bi-plus-circle text-lg"></i>
                        </x-primary-button>
                    </div>
                </div>

                {{-- 🔹 Tabel Barang Keluar --}}
                @include('item-outs.partials._table')
            </div>
        </div>
    </div>

    {{-- 🔹 Modal Tambah --}}
    @include('item-outs.partials._create-modal')

    {{-- 🔹 Script --}}
    @include('item-outs.partials._script')
</x-app-layout>
