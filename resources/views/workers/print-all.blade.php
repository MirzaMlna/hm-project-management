<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cetak Semua ID Card Tukang
        </h2>
    </x-slot>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-cards,
            #printable-cards * {
                visibility: visible;
            }

            #printable-cards {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                background: white;
                padding: 0;
                margin: 0;
            }

            #printable-cards>div {
                page-break-inside: avoid;
                border: 1px solid #000;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* pastikan warna background ikut tercetak */
            #printable-cards .card-header {
                background-color: #0369a1 !important;
                /* sky-700 */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: white !important;
                font-weight: bold;
                text-align: center;
                padding: 8px 4px;
                border-bottom: 1px solid #000;
            }

            #print-btn {
                display: none;
            }
        }

        /* preview layar */
        #printable-cards .card-header {
            background-color: #0369a1;
            /* sky-700 */
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px 4px;
            border-bottom: 1px solid #000;
        }
    </style>

    <div class="py-6">
        <div id="printable-cards" class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($workers as $worker)
                {{-- ID Card --}}
                <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-300">
                    {{-- HEADER --}}
                    <div class="card-header">
                        <h1 class="text-lg font-bold uppercase tracking-widest">HM Company</h1>
                        <p class="text-sm font-normal">Kartu Identitas Tukang</p>
                    </div>

                    {{-- BODY --}}
                    <div class="p-5 flex flex-col items-center">
                        @if ($worker->photo)
                            <img src="{{ asset('storage/' . $worker->photo) }}" alt="{{ $worker->name }}"
                                class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300 mb-3">
                        @else
                            <div
                                class="w-24 h-24 flex items-center justify-center bg-gray-200 rounded-lg mb-3 text-gray-500 italic">
                                No Photo
                            </div>
                        @endif

                        <h2 class="text-sm font-bold text-gray-800">{{ $worker->name }}</h2>
                        <p class="text-xs text-gray-500 mb-4">Kode: {{ $worker->code }}</p>

                        <div id="qr-{{ $worker->id }}" data-hash="{{ $worker->hash }}"></div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="bg-gray-100 p-2 text-center text-xs text-gray-500 border-t">
                        Berlaku selama menjadi pekerja di HM Company
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol Print --}}
        <div id="print-btn" class="mt-6 flex justify-center">
            <button onclick="window.print()"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500 shadow">
                Cetak Semua
            </button>
        </div>
    </div>

    {{-- QR Code --}}
    <script src="https://cdn.jsdelivr.net/npm/qr-code-styling/lib/qr-code-styling.js"></script>
    <script>
        document.querySelectorAll('[data-hash]').forEach(el => {
            const hash = el.dataset.hash;
            const qr = new QRCodeStyling({
                width: 100,
                height: 100,
                type: "svg",
                data: hash,
                dotsOptions: {
                    type: "extra-rounded"
                },
                imageOptions: {
                    crossOrigin: "anonymous",
                    margin: 5
                }
            });
            qr.append(el);
        });
    </script>
</x-app-layout>
