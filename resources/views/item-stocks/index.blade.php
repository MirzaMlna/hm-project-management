<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stok Barang
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
                {{-- 🔹 Filter + Tombol Aksi --}}
                @include('item-stocks.partials._filter-actions')

                {{-- 🔹 Loop per kategori --}}
                @include('item-stocks.partials._category-loop')
            </div>
        </div>
    </div>

    {{-- 🔹 Modal Tambah & Edit --}}
    @include('item-stocks.partials._create-modal')
    @include('item-stocks.partials._edit-modal')
    {{-- Script --}}
    @include('item-stocks.partials._script')
    
</x-app-layout>

