<?php

namespace App\Exports;

use App\Models\Accperkiraan;
use App\Models\Accmutasi;
use App\Models\Acc_sa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class NeracaExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;

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
            ->where('bulan', '<=', $bulan) // Semua mutasi hingga bulan yang dipilih
            ->pluck('kode_acc')
            ->unique();

        if ($kode_accs->isEmpty()) {
            return new Collection();
        }

        // Ambil akun pendapatan (kelompok 1) dari tabel accperkiraan
        $pendapatan = Accperkiraan::whereIn('kode_acc', $kode_accs)
            ->where('kelompok', 1) // Kelompok untuk pendapatan
            ->get();

        // Ambil akun kewajiban (kelompok 2 & 3) dari tabel accperkiraan
        $kewajiban = Accperkiraan::whereIn('kode_acc', $kode_accs)
            ->whereIn('kelompok', [2, 3]) // Kelompok untuk kewajiban
            ->get();

        $data = [];
        $totalPendapatan = 0;
        $totalKewajiban = 0;

        // Hitung saldo pendapatan
        foreach ($pendapatan as $akun) {
            $saldoAwal = Acc_sa::where('kode_acc', $akun->kode_acc)->where('tahun', '<', $tahun)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
                ->where('tahun', $tahun)
                ->whereBetween('bulan', [1, $bulan])
                ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                ->first();

            $saldo = $saldoAwal->total_debet + $mutasi->total_debet - ($saldoAwal->total_kredit + $mutasi->total_kredit);
            $totalPendapatan += $saldo;

            $data[] = [
                'kode_acc_pendapatan' => $akun->kode_acc,
                'nama_acc_pendapatan' => $akun->nama_acc,
                'saldo_pendapatan' => $saldo,
                'kosong' => '',
                'kode_acc_kewajiban' => null,
                'nama_acc_kewajiban' => null,
                'saldo_kewajiban' => null,
            ];
        }

        // Hitung saldo kewajiban
        $index = 0;
        foreach ($kewajiban as $akun) {
            $saldoAwal = Acc_sa::where('kode_acc', $akun->kode_acc)->where('tahun', '<', $tahun)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
                ->where('tahun', $tahun)
                ->whereBetween('bulan', [1, $bulan])
                ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                ->first();

            $saldo = $saldoAwal->total_kredit + $mutasi->total_kredit - ($saldoAwal->total_debet + $mutasi->total_debet);
            $totalKewajiban += $saldo;

            if (isset($data[$index])) {
                $data[$index]['kode_acc_kewajiban'] = $akun->kode_acc;
                $data[$index]['nama_acc_kewajiban'] = $akun->nama_acc;
                $data[$index]['saldo_kewajiban'] = $saldo;
            } else {
                $data[] = [
                    'kode_acc_pendapatan' => null,
                    'nama_acc_pendapatan' => null,
                    'saldo_pendapatan' => null,
                    'kosong' => '',
                    'kode_acc_kewajiban' => $akun->kode_acc,
                    'nama_acc_kewajiban' => $akun->nama_acc,
                    'saldo_kewajiban' => $saldo,
                ];
            }
            $index++;
        }

      $labaDitahan = $totalPendapatan - $totalKewajiban;

      // Tambahkan baris Laba Ditahan
      $data[] = [
          'kode_acc_pendapatan' => null,
          'nama_acc_pendapatan' => null,
          'saldo_pendapatan' => null,
          'kosong' => '',
          'kode_acc_kewajiban' => '3-0000',
          'nama_acc_kewajiban' => 'LABA DITAHAN',
          'saldo_kewajiban' => $labaDitahan,
      ];

      // Tambahkan Total Pendapatan
      $data[] = [
          'kode_acc_pendapatan' => 'TOTAL PENDAPATAN',
          'nama_acc_pendapatan' => '',
          'saldo_pendapatan' => $totalPendapatan,
          'kosong' => '',
          'kode_acc_kewajiban' => 'TOTAL KEWAJIBAN DAN EKUITAS',
          'nama_acc_kewajiban' => '',
          'saldo_kewajiban' => $totalKewajiban + $labaDitahan,
      ];
        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Kode COA', 'Nama Akun', 'Saldo', '', 'Kode COA', 'Nama Akun'];
    }

    public function styles(Worksheet $sheet)
    {
        // Set column widths for readability
        $sheet->getColumnDimension('A')->setWidth(15); // Kode COA (Pendapatan)
        $sheet->getColumnDimension('B')->setWidth(40); // Nama Akun (Pendapatan)
        $sheet->getColumnDimension('C')->setWidth(20); // Saldo (Pendapatan)
        $sheet->getColumnDimension('E')->setWidth(15); // Kode COA (Kewajiban)
        $sheet->getColumnDimension('F')->setWidth(40); // Nama Akun (Kewajiban)
        $sheet->getColumnDimension('G')->setWidth(20); // Saldo (Kewajiban)
        $sheet->getColumnDimension('H')->setWidth(15); // Kosong
        $sheet->getColumnDimension('I')->setWidth(20); // Laba Ditahan

        // Center align headings
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    public function title(): string
    {
        return 'Neraca Report';
    }
}
