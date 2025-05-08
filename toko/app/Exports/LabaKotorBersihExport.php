<?php

namespace App\Exports;

use App\Models\Accperkiraan;
use App\Models\Accmutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LabaKotorBersihExport implements FromCollection, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $toko_id;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        // Ambil daftar kode akun yang memiliki mutasi pada tahun dan bulan yang dipilih
        $kode_accs = Accmutasi::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->pluck('kode_acc')
            ->unique();

        if ($kode_accs->isEmpty()) {
            return new Collection();
        }

        // Ambil akun Pendapatan (Kelompok 4)
        $pendapatan = Accperkiraan::whereIn('kode_acc', $kode_accs)
            ->where('kelompok', 4)
            ->get();

        // Ambil akun HPP (Kelompok 5)
        $hpp = Accperkiraan::whereIn('kode_acc', $kode_accs)
            ->where('kelompok', 5)
            ->get();

        // Ambil akun Biaya (Kelompok 6)
        $biaya = Accperkiraan::whereIn('kode_acc', $kode_accs)
            ->where('kelompok', 6)
            ->get();

        $data = [];
        $totalPendapatan = 0;
        $totalHpp = 0;
        $totalBiaya = 0;

        // Process Pendapatan (Kelompok 4)
        $data[] = ['Pendapatan', '', '']; // Group Header
        $data[] = ['Kode Akun', 'Nama Akun', 'Saldo']; // Column Headers
        foreach ($pendapatan as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan) // Hanya bulan yang dipilih
                ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                ->first();

            $saldo = $mutasi ? $mutasi->total_kredit - $mutasi->total_debet : 0;
            $totalPendapatan += $saldo;

            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }
        $data[] = ['Total Pendapatan', '', $totalPendapatan];

        // Process HPP (Kelompok 5)
        $data[] = ['HPP', '', '']; // Group Header
        $data[] = ['Kode Akun', 'Nama Akun', 'Saldo']; // Column Headers
        foreach ($hpp as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan) // Hanya bulan yang dipilih
                ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                ->first();

            $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
            $totalHpp += $saldo;

            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }
        $data[] = ['Total HPP', '', $totalHpp];

        // Calculate Laba Kotor
        $labaKotor = $totalPendapatan - $totalHpp;
        $data[] = ['Laba Kotor', '', $labaKotor];

        // Process Biaya (Kelompok 6)
        $data[] = ['Biaya', '', '']; // Group Header
        $data[] = ['Kode Akun', 'Nama Akun', 'Saldo']; // Column Headers
        foreach ($biaya as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan) // Hanya bulan yang dipilih
                ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                ->first();

            $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
            $totalBiaya += $saldo;

            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }
        $data[] = ['Total Biaya', '', $totalBiaya];

        // Calculate Laba/Rugi Bersih
        $labaRugiBersih = $labaKotor - $totalBiaya;
        $data[] = ['Laba/Rugi Bersih', '', $labaRugiBersih];

        return new Collection($data);
    }

    public function title(): string
    {
        return 'Laba Kotor/Bersih';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Custom styling for headers
            'A1:C1' => [
                'font' => ['bold' => true],
                'alignment' => Alignment::HORIZONTAL_CENTER,
            ],
            // Add styling to the group headers
            'A2:A100' => [
                'font' => ['bold' => true],
                'alignment' => Alignment::HORIZONTAL_LEFT,
            ],
            // Style for the column headings
            'A3:C3' => [
                'font' => ['bold' => true],
                'alignment' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
    }
}