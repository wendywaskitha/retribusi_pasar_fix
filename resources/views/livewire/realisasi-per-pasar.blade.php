<div class="p-6 bg-white rounded-lg shadow-lg">
    <h2 class="mb-6 text-2xl font-semibold text-gray-800">Realisasi Per Pasar</h2>

    <div class="flex flex-col items-center justify-between mb-6 space-y-4 md:flex-row md:space-y-0">
        <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2">
            <div class="flex items-center">
                <span class="mr-2 text-gray-600">Dari:</span>
                <input type="date" wire:model="fromDate"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-gray-600">Sampai:</span>
                <input type="date" wire:model="toDate"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button wire:click="refreshData"
                class="flex items-center px-4 py-2 text-green-600 transition duration-150 ease-in-out bg-green-600 rounded-md hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Kirim
            </button>
        </div>
        <div class="flex space-x-2">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Cari pasar..."
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
        <table class="relative w-full whitespace-no-wrap bg-white border-collapse table-auto table-striped">
            <thead>
                <tr class="text-left">
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Nama Pasar</th>
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Total Pedagang</th>
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Sudah Bayar</th>
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Belum Bayar</th>
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Total Realisasi</th>
                    <th
                        class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">
                        Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pasars as $pasar)
                    <tr>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $pasar->name }}</td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $pasar->total_pedagang }}</td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span style="color: green;">{{ $pasar->pedagang_sudah_bayar }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span style="color: red;">{{ $pasar->pedagang_belum_bayar }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span class="font-medium text-blue-600">Rp
                                {{ number_format($pasar->total_realisasi, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <div class="flex items-center gap-2">
                                @php
                                    $percentage = floatval($pasar->persentase_realisasi);
                                    $color = match (true) {
                                        $percentage >= 80 => '#10B981', // emerald-600
                                        $percentage >= 50 => '#D97706', // amber-600
                                        default => '#DC2626', // rose-600
                                    };
                                @endphp

                                <div class="min-w-[60px] text-sm font-medium" style="color: {{ $color }}">
                                    {{ number_format($percentage, 2) }}%
                                </div>

                                <div class="flex-1 h-2 overflow-hidden bg-gray-200 rounded-full">
                                    <div class="h-full transition-all duration-300"
                                        style="width: {{ min($percentage, 100) }}%; background-color: {{ $color }}">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

                <!-- Total Row -->
                <tr class="font-bold bg-gray-50">
                    <td class="px-6 py-4 border-t-2 border-gray-200">Total Keseluruhan</td>
                    <td class="px-6 py-4 border-t-2 border-gray-200">
                        {{ $totalPedagang }}
                    </td>
                    <td class="px-6 py-4 border-t-2 border-gray-200">
                        <span class="text-green-600">
                            {{ $totalSudahBayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4 border-t-2 border-gray-200">
                        <span class="text-red-600">
                            {{ $totalBelumBayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4 border-t-2 border-gray-200">
                        <span class="text-blue-600">
                            Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 border-t-2 border-gray-200">
                        @php
                            $totalPercentage = $totalPedagang > 0 ? ($totalSudahBayar / $totalPedagang) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <span class="mr-2 text-sm font-medium">
                                {{ number_format($totalPercentage, 2) }}%
                            </span>
                            <div class="relative w-full h-2 bg-gray-200 rounded">
                                <div style="width: {{ $totalPercentage }}%"
                                    class="absolute top-0 h-2 bg-green-500 rounded"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pasars->links() }}
    </div>

    <!-- Add per page selector -->
    <div class="flex items-center mt-4">
        <label for="perPage" class="mr-2 text-gray-600">Show:</label>
        <select wire:model="perPage" id="perPage"
            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</div>
