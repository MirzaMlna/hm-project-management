<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Barang Masuk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 p-4 rounded bg-green-100 text-green-800 shadow-sm transition-opacity duration-500">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Filter & tombol --}}
                <div class="flex flex-wrap justify-between items-center px-6 mt-6 gap-3">
                    {{-- Filter Bulan --}}
                    <form method="GET" action="{{ route('item-ins.index') }}" class="flex items-center gap-2">
                        <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                            Filter Bulan:
                        </label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}"
                            class="border-gray-300 rounded p-2 text-sm focus:border-sky-500 focus:ring-sky-500 transition"
                            onchange="this.form.submit()">
                    </form>

                    {{-- Tombol kanan --}}
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2">
                            {{-- Tombol Tambah --}}
                            <x-primary-button
                                class="!bg-sky-600 !hover:bg-sky-700 w-10 h-10 flex items-center justify-center"
                                onclick="toggleCreateModal()" title="Tambah Barang Masuk">
                                <i class="bi bi-plus-circle text-lg"></i>
                            </x-primary-button>

                            {{-- Tombol Export --}}
                            <x-primary-button
                                class="!bg-green-600 !hover:bg-green-700 w-10 h-10 flex items-center justify-center"
                                title="Export Excel">
                                <a href="{{ route('item-ins.export', ['month' => $selectedMonth]) }}"
                                    class="flex items-center justify-center w-full h-full text-white">
                                    <i class="bi bi-file-spreadsheet text-lg"></i>
                                </a>
                            </x-primary-button>
                        </div>

                    </div>
                </div>

                {{-- Tabel --}}
                @include('item-ins.partials._table')
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @include('item-ins.partials._create-modal')

    {{-- Script --}}
    @include('item-ins.partials._script')
</x-app-layout>
