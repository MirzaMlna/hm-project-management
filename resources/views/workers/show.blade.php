<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ID Card Tukang') }}
        </h2>
    </x-slot>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-card,
            #printable-card * {
                visibility: visible;
            }

            #printable-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
                box-shadow: none;
                background: white;
                grid-gap: 1rem;
            }

            #printable-card>div {
                box-shadow: none !important;
                border-radius: 0 !important;
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }

            /* Tampilkan background biru header */
            #printable-card>div>div.bg-sky-800 {
                background-color: #00598A !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .mt-6.flex.justify-center.gap-3 {
                display: none;
            }

            #qr svg {
                width: 200px !important;
                height: 200px !important;
            }
        }
    </style>

    <div class="py-6">

        <div id="printable-card" class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- ================= DEPAN ================= --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-300">
                {{-- Header --}}
                <div class="bg-sky-800 text-white text-center py-3">
                    <h1 class="text-lg font-bold uppercase tracking-widest">HM Company</h1>
                    <p class="text-sm">Kartu Identitas Tukang</p>
                </div>

                {{-- Foto + Data --}}
                <div class="p-5 flex flex-col items-center">
                    {{-- Foto --}}
                    @if ($worker->photo)
                        <img src="{{ asset('storage/' . $worker->photo) }}" alt="{{ $worker->name }}"
                            class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 mb-4 shadow">
                    @else
                        <div
                            class="w-32 h-32 flex items-center justify-center bg-gray-200 rounded-lg mb-4 text-gray-500 italic">
                            Tidak ada foto
                        </div>
                    @endif

                    {{-- Kode & Nama --}}
                    <p class="text-sm text-gray-500">Kode: {{ $worker->code }}</p>
                    <h2 class="text-lg font-bold text-gray-800">{{ $worker->name }}</h2>

                    <div class="mt-4 text-center" id="qr"></div>
                </div>

                {{-- Footer --}}
                <div class="bg-gray-100 p-2 text-center text-xs text-gray-500 border-t">
                    Berlaku selama menjadi pekerja di HM Company
                </div>
            </div>

            {{-- ================= BELAKANG ================= --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-300">
                {{-- Header --}}
                <div class="bg-sky-800 text-white text-center py-3">
                    <h1 class="text-lg font-bold uppercase tracking-widest">HM Company</h1>
                    <p class="text-sm">Informasi Pekerja</p>
                </div>

                {{-- Info Detail --}}
                <div class="p-5 text-sm space-y-1">
                    <p><span class="font-semibold">Kode :</span> {{ $worker->code }}</p>
                    <hr>

                    <p><span class="font-semibold">Nama :</span> {{ $worker->name }}</p>
                    <hr>

                    <p><span class="font-semibold">No. Telepon :</span> {{ $worker->phone }}</p>
                    <hr>

                    <p>
                        <span class="font-semibold">Tanggal Lahir :</span>
                        {{ $worker->birth_date ? \Carbon\Carbon::parse($worker->birth_date)->translatedFormat('j F Y') : '-' }}
                    </p>
                    <hr>

                    <p><span class="font-semibold">Alamat :</span> {{ $worker->address }}</p>
                    <hr>

                    @if ($worker->note)
                        <p><span class="font-semibold">Catatan :</span> {{ $worker->note }}</p>
                    @endif
                </div>
                <div class="flex justify-center">
                    <x-application-logo />
                </div>
                <div class=" p-2 text-center text-xs text-gray-500">
                    Berlaku selama menjadi pekerja di HM Company
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-sky-800 text-white rounded hover:bg-sky-700 shadow">
                Cetak ID Card
            </button>
            <a href="{{ route('workers.edit', $worker) }}"
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 shadow">
                Edit
            </a>
            <a href="{{ route('workers.index') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 shadow">
                Kembali
            </a>
        </div>

    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/qr-code-styling/lib/qr-code-styling.js"></script>

<script type="text/javascript">
    const qrCode = new QRCodeStyling({
        width: 150,
        height: 150,
        margin: 10,
        type: "svg",
        errorCorrectionLevel: "M",
        data: "{{ $qrCode }}",
        dotsOptions: {
            color: "black",
            type: "extra-rounded",
        },
        imageOptions: {
            crossOrigin: "anonymous",
            margin: 5
        }
    });

    qrCode.append(document.getElementById("qr"));
    window.print();
</script>
