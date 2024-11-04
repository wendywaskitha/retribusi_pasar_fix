<?php

namespace App\Livewire;

use App\Models\Pasar;
use Livewire\Component;
use Livewire\WithPagination;

class RealisasiPerPasar extends Component
{
    // public function render()
    // {
    //     return view('livewire.realisasi-per-pasar');
    // }

    use WithPagination;

    public $fromDate;
    public $toDate;
    public $search = '';
    public $perPage = 25; // Default per page value

    public $totalPedagang = 0;
    public $totalSudahBayar = 0;
    public $totalBelumBayar = 0;
    public $totalRealisasi = 0;

    public function mount()
    {
        // Set tanggal default ke hari ini
        $this->fromDate = now()->startOfDay()->toDateString();
        $this->toDate = now()->endOfDay()->toDateString();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        $pasars = $query->paginate($this->perPage);

        $this->calculateTotals($query);

        $pasars->getCollection()->transform(function ($pasar) {
            $pasar->pedagang_belum_bayar = $pasar->total_pedagang - $pasar->pedagang_sudah_bayar;
            $pasar->persentase_realisasi = $pasar->total_pedagang > 0
                ? ($pasar->pedagang_sudah_bayar / $pasar->total_pedagang) * 100
                : 0;
            return $pasar;
        });

        return view('livewire.realisasi-per-pasar', [
            'pasars' => $pasars,
        ]);
    }

    private function getFilteredQuery()
    {
        return Pasar::query()
            ->withCount(['pedagangs as total_pedagang'])
            ->withCount(['pedagangs as pedagang_sudah_bayar' => function ($query) {
                $query->whereHas('retribusiPembayarans', function ($q) {
                    $q->whereBetween('tanggal_bayar', [$this->fromDate, $this->toDate]);
                });
            }])
            ->withSum(['retribusiPembayarans as total_realisasi' => function ($query) {
                $query->whereBetween('tanggal_bayar', [$this->fromDate, $this->toDate]);
            }], 'total_biaya')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
    }

    private function calculateTotals($query)
    {
        $totals = $query->get();
        $this->totalPedagang = $totals->sum('total_pedagang');
        $this->totalSudahBayar = $totals->sum('pedagang_sudah_bayar');
        $this->totalBelumBayar = $this->totalPedagang - $this->totalSudahBayar;
        $this->totalRealisasi = $totals->sum('total_realisasi');
    }

    public function updatedPerPage($value)
    {
        $this->resetPage(); // Reset page number when per page changes
    }

    public function refreshData()
    {
        // Method ini akan dipanggil ketika tombol refresh ditekan
        $this->resetPage();
    }
}
