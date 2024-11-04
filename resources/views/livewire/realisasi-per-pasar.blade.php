<div class="p-6 bg-white rounded-lg shadow-lg">
    <h2 class="mb-6 text-2xl font-semibold text-gray-800">Realisasi Per Pasar</h2>

    <div class="flex flex-col items-center justify-between mb-6 space-y-4 md:flex-row md:space-y-0">
        <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2">
            <div class="flex items-center">
                <span class="mr-2 text-gray-600">Dari:</span>
                <input type="date" wire:model="fromDate" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-gray-600">Sampai:</span>
                <input type="date" wire:model="toDate" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
        <div class="flex space-x-2">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Cari pasar..." class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <button wire:click="refreshData" class="flex items-center px-4 py-2 text-green-600 transition duration-150 ease-in-out bg-green-600 rounded-md hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
        </div>
    </div>

    <div class="relative overflow-x-auto overflow-y-auto bg-white rounded-lg shadow">
        <table class="relative w-full whitespace-no-wrap bg-white border-collapse table-auto table-striped">
            <thead>
                <tr class="text-left">
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Nama Pasar</th>
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Total Pedagang</th>
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Sudah Bayar</th>
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Belum Bayar</th>
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Total Realisasi</th>
                    <th class="sticky top-0 px-6 py-3 text-xs font-bold tracking-wider text-gray-600 uppercase bg-gray-100 border-b border-gray-200">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pasars as $pasar)
                    <tr>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $pasar->name }}</td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">{{ $pasar->total_pedagang }}</td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span class="font-medium text-green-600">{{ $pasar->pedagang_sudah_bayar }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span class="font-medium text-red-600">{{ $pasar->pedagang_belum_bayar }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <span class="font-medium text-blue-600">Rp {{ number_format($pasar->total_realisasi, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 border-t border-gray-200 border-dashed">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm font-medium">{{ number_format($pasar->persentase_realisasi, 2) }}%</span>
                                <div class="relative w-full h-2 bg-gray-200 rounded">
                                    <div style="width: {{ $pasar->persentase_realisasi }}%"
                                         class="absolute top-0 h-2 bg-green-500 rounded"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pasars->links() }}
    </div>
</div>
