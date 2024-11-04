<div class="px-4 py-3 bg-gray-50">
    <dl class="grid grid-cols-5 gap-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Total Pedagang</dt>
            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($totalPedagang) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Sudah Bayar</dt>
            <dd class="mt-1 text-sm font-semibold text-green-600">{{ number_format($totalSudahBayar) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Belum Bayar </dt>
            <dd class="mt-1 text-sm font-semibold text-red-600">{{ number_format($totalBelumBayar) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Total Realisasi</dt>
            <dd class="mt-1 text-sm font-semibold text-gray-900">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Persentase Realisasi</dt>
            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $persentaseRealisasi }}</dd>
        </div>
    </dl>
</div>
