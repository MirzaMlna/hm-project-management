<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tukang
            </h2>
            <a href="{{ route('workers.inactive') }}">
                <x-primary-button class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                    <i class="bi bi-person-slash me-2"></i> Tukang Nonaktif
                </x-primary-button>
            </a>
        </div>

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

            @include('workers.partials._cards')

            <!-- Tabel Tukang -->
            <div class="bg-white rounded-xl shadow-lg p-6">


                @include('workers.partials._buttons')
                @include('workers.partials._table')

                <div class="mt-4">
                    {{ $workers->links() }}
                </div>
            </div>
        </div>
    </div>


    @include('workers.partials._deactive-modal');
    @include('workers.partials._excel-export-modal');

</x-app-layout>

@include('workers.partials._script')
