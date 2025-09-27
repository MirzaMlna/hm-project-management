<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Tukang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-end items-end">
                    <x-primary-button class="mt-6 me-6">
                        Tambah Kategori Tukang
                    </x-primary-button>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-sky-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Kategori
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Jumlah Tukang
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-50 border-b border-gray-200 text-gray-900">
                                    <th scope="row" class="px-6 py-4 font-medium  whitespace-nowrap">
                                        1
                                    </th>
                                    <td class="px-6 py-4">
                                        Jawa
                                    </td>
                                    <td class="px-6 py-4">
                                        30
                                    </td>
                                    <td class="px-6 py-4">
                                        <i class="bi bi-pencil-square text-yellow-500"></i>
                                        <i class="bi bi-trash3 text-red-500"></i>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
