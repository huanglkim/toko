<?php

namespace App\Exports;

use App\Models\Pbhd;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanhutangExport implements FromCollection, WithHeadings
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
                    ->where('hutang', '>', 0) 
                    ->where('status_hutang', 1)
                    ->with('suplier') 
                    ->get()
                    ->map(function($pb) {
                        return [
                            'Invoice' => $pb->invoice,
                            'Tanggal' => TanggalIndo($pb->tanggal), // Pastikan fungsi TanggalIndo tersedia
                            'Suplier' => $pb->suplier ? $pb->suplier->nama : 'Tidak Ditemukan', 
                            'Total Hutang' => $pb->hutang,
                            'Status' => $pb->status_hutang == 1 ? 'Belum Lunas' : 'Lunas', // Menampilkan status hutang yang sesuai
                        ];
                    });
    }

    public function headings(): array
    {
        return ["Invoice","Tanggal", "Suplier", "Total Hutang", "Status"];
    }
}
