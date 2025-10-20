<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Card utama --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Tombol Tambah --}}
                <div class="flex justify-end px-6 mt-6">
                    <x-primary-button class="w-10 h-10 flex items-center justify-center" onclick="toggleCreateModal()"
                        title="Tambah Kategori">
                        <i class="bi bi-plus-circle text-lg"></i>
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                @include('item-categories.partials.table')
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @include('item-categories.partials.create-modal')

    {{-- Modal Edit --}}
    @include('item-categories.partials.edit-modal')

    {{-- Script --}}
    @include('item-categories.partials.script')
</x-app-layout>
