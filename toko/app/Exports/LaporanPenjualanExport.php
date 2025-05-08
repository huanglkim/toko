<?php

namespace App\Exports;

use App\Models\Pjhd;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPenjualanExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal, $tanggal_akhir;

    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        $this->tanggal_awal = Carbon::parse($tanggal_awal)->startOfDay();
        $this->tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    }

    public function collection()
    {
        return Pjhd::whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                    ->with('pelanggan')  // Pastikan relasi ini ada di model Pjhd
                    ->get()
                    ->map(function($pj) {
                        return [
                            'Invoice' => $pj->invoice,
                            'Tanggal' => TanggalIndo($pj->tanggal),
                            'Pelanggan' => $pj->pelanggan ? $pj->pelanggan->nama : 'Tidak Ditemukan',  // Keamanan jika relasi tidak ada
                            'Dpp' => $pj->dpp,
                            'Ppn' => $pj->ppn,
                            'Total Penjualan' => $pj->total
                        ];
                    });
    }

    public function headings(): array
    {
        return ["Invoice","Tanggal", "Pelanggan", "Dpp", "Ppn", "Total Penjualan"];
    }
}
