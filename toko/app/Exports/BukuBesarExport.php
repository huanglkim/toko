<?php

namespace App\Exports;

use App\Models\Acc_sa;
use App\Models\Accjurnal;
use App\Models\Accmutasi;
use App\Models\Accperkiraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BukuBesarExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    protected $tanggal_awal;
    protected $tanggal_akhir;

    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection()
    {
        $kode_acc = request('kode_acc');
        $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();
        $posisi = $accperkiraan->posisi;
        $saldo_awal = $this->hitungSaldoAwal($kode_acc, $posisi, $this->tanggal_awal);

        $accjurnals = Accjurnal::where('kode_acc', $kode_acc)
            ->whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
            ->get();

        $saldo = $saldo_awal['hasil'];
        $data = [];

        foreach ($accjurnals as $accjurnal) {
            $saldo += $posisi == 'D' ? $accjurnal->debet - $accjurnal->kredit : $accjurnal->kredit - $accjurnal->debet;

            $kode_lawan = $accjurnal->kode_lawan ?? '-';
            $nama_lawan = $accjurnal->accperkiraanLawan->nama_acc ?? '-';

            $data[] = [
                Carbon::parse($accjurnal->tanggal)->format('Y-m-d'),
                $accjurnal->invoice ?? '-',
                $accjurnal->keterangan ?? '-',
                $kode_lawan . ' / ' . $nama_lawan,
                'Rp. ' . number_format($accjurnal->debet, 2, ',', '.'),
                'Rp. ' . number_format($accjurnal->kredit, 2, ',', '.'),
                'Rp. ' . number_format($saldo, 2, ',', '.'),
            ];
        }

        $total_debet = $accjurnals->sum('debet');
        $total_kredit = $accjurnals->sum('kredit');

        $data[] = [
            'Total', '', '', '',
            'Rp. ' . number_format($total_debet, 2, ',', '.'),
            'Rp. ' . number_format($total_kredit, 2, ',', '.'),
            'Rp. ' . number_format($saldo, 2, ',', '.'),
        ];

        return collect($data);
    }

    public function headings(): array
    {
        $kode_acc = request('kode_acc');
        $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();
        $saldo_awal = $this->hitungSaldoAwal($kode_acc, $accperkiraan->posisi, $this->tanggal_awal);

        return [
            ['Kode Akun: ' . $kode_acc, 'Nama Akun: ' . $accperkiraan->nama_acc, 'Posisi: ' . $accperkiraan->posisi, 'Saldo Awal: Rp. ' . number_format($saldo_awal['hasil'], 2, ',', '.')],
            ['Tanggal', 'Invoice', 'Keterangan', 'Kode Lawan', 'Debet', 'Kredit', 'Saldo'],
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    private function hitungSaldoAwal($kode_acc, $posisi, $tanggal_awal)
    {
        $toko_id = 1;
        $saldo_awal = ['tahun' => 0, 'bulan' => 0, 'hari' => 0];

        $tahun = date('Y', strtotime($tanggal_awal));
        $bulan = date('m', strtotime($tanggal_awal));

        $acc_sa = Acc_sa::where('tahun', $tahun)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->first();
        if ($acc_sa) {
            $saldo_awal['tahun'] = $posisi == 'D' ? $acc_sa->debet - $acc_sa->kredit : $acc_sa->kredit - $acc_sa->debet;
        }

        $accmutasi = Accmutasi::where('tahun', $tahun)->where('bulan', '<', $bulan)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->get();
        if ($accmutasi->count() > 0) {
            $saldo_awal['bulan'] = $posisi == 'D' ? $accmutasi->sum('debet') - $accmutasi->sum('kredit') : $accmutasi->sum('kredit') - $accmutasi->sum('debet');
        }

        $accjurnal = Accjurnal::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->whereDate('tanggal', '<', $tanggal_awal)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->get();
        if ($accjurnal->count() > 0) {
            $saldo_awal['hari'] = $posisi == 'D' ? $accjurnal->sum('debet') - $accjurnal->sum('kredit') : $accjurnal->sum('kredit') - $accjurnal->sum('debet');
        }

        $saldo_awal['hasil'] = $saldo_awal['tahun'] + $saldo_awal['bulan'] + $saldo_awal['hari'];
        return $saldo_awal;
    }
}
