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
        $query = Pasar::query()
            ->withCount(['pedagangs as total_pedagang'])
            ->withCount(['pedagangs as pedagang_sudah_bayar' => function ($query) {
                $query->whereHas('retribusiPembayarans', function ($q) {
                    $q->whereBetween('tanggal_bayar', [$this->fromDate, $this->toDate]);
                });
            }])
            ->withSum(['retribusiPembayarans as total_realisasi' => function ($query) {
                $query->whereBetween('tanggal_bayar', [$this->fromDate, $this->toDate]);
            }], 'total_biaya');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $pasars = $query->paginate(10);

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

    public function refreshData()
    {
        // Method ini akan dipanggil ketika tombol refresh ditekan
        $this->resetPage();
    }
}