<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pemasok Barang
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Header kiri-kanan --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-truck text-sky-700"></i> Daftar Pemasok
                    </h3>
                    <x-primary-button onclick="toggleCreateModal()" title="Tambah Pemasok">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                @include('item-suppliers.partials._table')
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @include('item-suppliers.partials._create-modal')
    @include('item-suppliers.partials._edit-modal')
    {{-- Script --}}

    @include('item-suppliers.partials._script')
</x-app-layout>
