<?php

namespace App\Exports;

use App\Models\Pjhd;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanpiutangExport implements FromCollection, WithHeadings
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
                    ->where('piutang', '>', 0) 
                    ->where('status_piutang', 1)
                    ->with('pelanggan') 
                    ->get()
                    ->map(function($pb) {
                        return [
                            'Invoice' => $pb->invoice,
                            'Tanggal' => TanggalIndo($pb->tanggal), // Pastikan fungsi TanggalIndo tersedia
                            'Pelanggan' => $pb->pelanggan ? $pb->pelanggan->nama : 'Tidak Ditemukan', 
                            'Total Piutang' => $pb->piutang,
                            'Status' => $pb->status_piutang == 1 ? 'Belum Lunas' : 'Lunas', // Menampilkan status piutang yang sesuai
                        ];
                    });
    }

    public function headings(): array
    {
        return ["Invoice","Tanggal", "Pelanggan", "Total Piutang", "Status"];
    }
}
