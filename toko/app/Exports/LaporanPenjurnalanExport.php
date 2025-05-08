<?php

namespace App\Exports;

use App\Models\Accjurnal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPenjurnalanExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal, $tanggal_akhir;

    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        $this->tanggal_awal = Carbon::parse($tanggal_awal)->startOfDay();
        $this->tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    }

    public function collection()
    {
        return Accjurnal::whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
                    ->with(['accperkiraan', 'accperkiraanLawan']) // Pastikan relasi juga di-load
                    ->get()
                    ->map(function($aj) {
                        return [
                            'Invoice' => $aj->invoice,
                            'Tanggal' => $this->TanggalIndo($aj->tanggal),
                            'Kode Account' => $aj->kode_acc, 
                            'Nama Account' => $aj->accperkiraan ? $aj->accperkiraan->nama_acc : 'Tidak Ditemukan',
                            'Debet' => $aj->debet,
                            'Kredit' => $aj->kredit,
                            'Lawan' => ($aj->accperkiraanLawan) ? $aj->kode_lawan . ' / ' . $aj->accperkiraanLawan->nama_acc : $aj->kode_lawan,
                            'Keterangan' => $aj->keterangan,
                        ];
                    });
    }

    public function headings(): array
    {
        return ["Invoice", "Tanggal", "Kode Account", "Nama Account", "Debet", "Kredit", "Lawan", "Keterangan"];
    }

    private function TanggalIndo($tanggal)
    {
        return Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}
