<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <!-- Total Workers -->
    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Total Tukang</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalWorkers }}</p>
        </div>
        <div class="bg-blue-100 p-3 rounded-full">
            <i class="bi bi-people text-blue-600 text-2xl"></i>
        </div>
    </div>
    <!-- Active Workers -->
    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Tukang Aktif</p>
            <p class="text-2xl font-bold text-gray-800">{{ $activeWorkers }}</p>
        </div>
        <div class="bg-green-100 p-3 rounded-full">
            <i class="bi bi-check-circle text-green-600 text-2xl"></i>
        </div>
    </div>
    <!-- Daily Salary -->
    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-amber-500 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Total Gaji Harian</p>
            <p class="text-2xl font-bold text-gray-800">
                Rp{{ number_format($totalDailySalary, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-amber-100 p-3 rounded-full">
            <i class="bi bi-wallet2 text-amber-600 text-2xl"></i>
        </div>
    </div>
</div>
