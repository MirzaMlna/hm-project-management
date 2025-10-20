<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Titik Pembangunan
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

            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-6">
                {{-- Header --}}
                <div class="flex justify-end mb-4">
                    <x-primary-button onclick="toggleCreateModal()" class="flex items-center gap-2 !w-auto">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                @include('development-points.partials.table')
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @include('development-points.partials.create-modal')

    {{-- Modal Edit --}}
    @include('development-points.partials.edit-modal')

    {{-- Script --}}
    @include('development-points.partials.script')
</x-app-layout>
