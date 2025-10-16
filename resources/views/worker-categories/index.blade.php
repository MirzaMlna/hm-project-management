<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Header + tombol tambah --}}
                <div class="flex justify-between items-center px-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class=""></i>
                    </h3>
                    <x-primary-button onclick="toggleCreateModal()" title="Tambah Kategori Tukang">
                        <i class="bi bi-plus-circle"></i>
                    </x-primary-button>
                </div>

                {{-- Tabel --}}
                @include('worker-categories.partials._table')

            </div>
        </div>
    </div>

    {{-- Modal Tambah & Edit --}}
    @include('worker-categories.partials._create-modal')
    @include('worker-categories.partials._edit-modal')

    {{-- Script --}}
    @include('worker-categories.partials._script')

</x-app-layout>
