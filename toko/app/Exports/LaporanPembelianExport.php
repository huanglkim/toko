<?php

namespace App\Exports;

use App\Models\Pbhd;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPembelianExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal, $tanggal_akhir;

    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        $this->tanggal_awal = Carbon::parse($tanggal_awal)->startOfDay();
        $this->tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    }

    public function collection()
    {
        return Pbhd::whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                    ->with('suplier')  // Pastikan relasi ini ada di model Pbhd
                    ->get()
                    ->map(function($pb) {
                        return [
                            'Invoice' => $pb->invoice,
                            'Tanggal' => TanggalIndo($pb->tanggal),
                            'Supplier' => $pb->suplier ? $pb->suplier->nama : 'Tidak Ditemukan',  // Keamanan jika relasi tidak ada
                            'Dpp' => $pb->dpp,
                            'Ppn' => $pb->ppn,
                            'Total Pembelian' => $pb->total
                        ];
                    });
    }

    public function headings(): array
    {
        return ["Invoice","Tanggal", "Supplier", "Dpp", "Ppn", "Total Pembelian"];
    }
}
