<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HM Company Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-sky-100 via-white to-sky-200 relative overflow-hidden">

        {{-- Background animasi gelembung --}}
        <div class="absolute inset-0 -z-10">
            <div class="w-72 h-72 bg-sky-300 rounded-full absolute top-10 left-10 opacity-20 animate-pulse"></div>
            <div
                class="w-80 h-80 bg-blue-400 rounded-full absolute bottom-20 right-20 opacity-20 animate-pulse delay-1000">
            </div>
        </div>

        {{-- Konten utama --}}
        <div class="text-center px-6 sm:px-10">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-sky-800 mb-4 animate-fade-in-down">
                Selamat Datang di <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-700 to-blue-500">
                    HM Project Management System
                </span>
            </h1>

            <p class="text-gray-600 text-lg sm:text-xl max-w-2xl mx-auto mb-8 animate-fade-in">
                Aplikasi internal untuk manajemen proyek, tukang, dan stok gudang.
                Didesain agar pekerjaan jadi lebih cepat, akurat, dan efisien.
            </p>

            <div class="flex flex-wrap justify-center gap-4 animate-fade-in-up">
                <a href="{{ route('dashboard') }}"
                    class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg shadow-md text-sm sm:text-base transition transform hover:scale-105">
                    <i class="bi bi-speedometer2 me-2"></i> Masuk ke Dashboard
                </a>

                <a href="{{ route('login') }}"
                    class="border border-sky-600 text-sky-700 hover:bg-sky-600 hover:text-white px-6 py-3 rounded-lg shadow-md text-sm sm:text-base transition transform hover:scale-105">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login Admin
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="absolute bottom-6 text-gray-500 text-sm text-center px-4">
            © {{ date('Y') }} HM Company Management System. All rights reserved.
        </footer>
    </div>

    {{-- Animasi CSS sederhana --}}
    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.8s ease-out;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out 0.3s both;
        }

        .animate-fade-in {
            animation: fade-in-up 0.8s ease-out 0.6s both;
        }
    </style>
</body>

</html>
