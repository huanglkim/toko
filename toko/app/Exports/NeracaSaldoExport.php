<?php

namespace App\Exports;

use App\Models\Accmutasi;
use App\Models\Acc_sa;
use App\Models\Accperkiraan;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NeracaSaldoExport implements FromCollection, WithHeadings, WithEvents
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        // Validate bulan and tahun
        $validator = Validator::make(
            ['bulan' => $bulan, 'tahun' => $tahun],
            [
                'bulan' => 'required|date_format:m',
                'tahun' => 'required|date_format:Y',
            ],
        );

        if ($validator->fails()) {
            // Throw an exception or handle error as needed
            throw new \InvalidArgumentException('Invalid parameters: ' . implode(', ', $validator->errors()->all()));
        }

        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $toko_id = 1;
    
        // Get accmutasi data for the specified bulan and tahun
        $kodeAkunList = Acc_sa::where('tahun', $tahun)
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->merge(Accmutasi::where('tahun', $tahun)->where('toko_id', $toko_id)->pluck('kode_acc'))
            ->unique();
    
        $data = [];
        $totalDebetAwal = 0;
        $totalKreditAwal = 0;
        $totalDebetMutasi = 0;
        $totalKreditMutasi = 0;
        $totalDebetSaldoAkhir = 0;
        $totalKreditSaldoAkhir = 0;
    
        foreach ($kodeAkunList as $kode_acc) {
            $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();
    
            if (!$accperkiraan) {
                continue;
            }
    
            // Ambil saldo awal dari tahun awal
            $saldoTahunAwal = Acc_sa::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
    
            // Ambil saldo akhir dari bulan-bulan sebelumnya (Januari hingga bulan sebelumnya)
            $saldoSebelumnya = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('bulan', '<', $bulan)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
    
            // Saldo awal = saldo tahun awal + saldo akhir bulan-bulan sebelumnya
            $debetAwal = ($saldoTahunAwal->total_debet ?? 0) + ($saldoSebelumnya->total_debet ?? 0);
            $kreditAwal = ($saldoTahunAwal->total_kredit ?? 0) + ($saldoSebelumnya->total_kredit ?? 0);
    
            // Ambil mutasi bulan berjalan
            $mutasiBulanIni = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
    
            $debetMutasi = $mutasiBulanIni->total_debet ?? 0;
            $kreditMutasi = $mutasiBulanIni->total_kredit ?? 0;

            $saldoAkhirDebet = $debetAwal + $debetMutasi;
            $saldoAkhirKredit = $kreditAwal + $kreditMutasi;
    
            // Add to totals
            $totalDebetAwal += $debetAwal;
            $totalKreditAwal += $kreditAwal;
            $totalDebetMutasi += $debetMutasi;
            $totalKreditMutasi += $kreditMutasi;
            $totalDebetSaldoAkhir += $saldoAkhirDebet;
            $totalKreditSaldoAkhir += $saldoAkhirKredit;
    
            // Prepare data row
            $data[] = [
                'kode_acc' => $kode_acc,
                'posisi' => $accperkiraan->posisi,
                'nama_acc' => $accperkiraan->nama_acc,
                'debetAwal' => $this->formatNumber($debetAwal),
                'kreditAwal' => $this->formatNumber($kreditAwal),
                'debetMutasi' => $this->formatNumber($debetMutasi),
                'kreditMutasi' => $this->formatNumber($kreditMutasi),
                'saldoAkhirDebet' => $this->formatNumber($saldoAkhirDebet),
                'saldoAkhirKredit' => $this->formatNumber($saldoAkhirKredit),
            ];
        }
    
        // Append totals row to the data collection
        $data[] = [
            'kode_acc' => 'Total', // Add "Total" label
            'posisi' => '',
            'nama_acc' => '',
            'debetAwal' => $this->formatNumber($totalDebetAwal),
            'kreditAwal' => $this->formatNumber($totalKreditAwal),
            'debetMutasi' => $this->formatNumber($totalDebetMutasi),
            'kreditMutasi' => $this->formatNumber($totalKreditMutasi),
            'saldoAkhirDebet' => $this->formatNumber($totalDebetSaldoAkhir),
            'saldoAkhirKredit' => $this->formatNumber($totalKreditSaldoAkhir),
        ];
    
        return collect($data);
    }

    // Format number as simple number, replace empty with dash (-)
    private function formatNumber($amount)
    {
        return $amount ? $amount : '-';
    }

    public function headings(): array
    {
        return [
            ['Kode COA', 'Saldo Normal', 'Nama Akun', 'Saldo Awal', '', 'Mutasi', 'Saldo Akhir', ''],
            ['', '', '', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit', ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merging cells for header rows
                $event->sheet->mergeCells('A1:A2');
                $event->sheet->mergeCells('B1:B2');
                $event->sheet->mergeCells('C1:C2');
                $event->sheet->mergeCells('D1:E1');
                $event->sheet->mergeCells('F1:G1');
                $event->sheet->mergeCells('H1:I1');
                $event->sheet->setCellValue('D1', 'Saldo Awal');
                $event->sheet->setCellValue('F1', 'Mutasi');
                $event->sheet->setCellValue('H1', 'Saldo Akhir');

                // Style headers
                $event->sheet->getStyle('A1:I1')->getFont()->setBold(true);
                $event->sheet->getStyle('A2:I2')->getFont()->setBold(true);

                // Apply border to all cells
                $event->sheet
                    ->getStyle('A1:I' . $event->sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Total formulas
                $rowCount = $event->sheet->getHighestRow();
                $totalDebetAwal = "D$rowCount";
                $totalKreditAwal = "E$rowCount";
                $totalDebetMutasi = "F$rowCount";
                $totalKreditMutasi = "G$rowCount";
                $totalDebetSaldoAkhir = "H$rowCount";
                $totalKreditSaldoAkhir = "I$rowCount";

                $event->sheet->setCellValue($totalDebetAwal, '=SUM(D3:D' . ($rowCount - 1) . ')');
                $event->sheet->setCellValue($totalKreditAwal, '=SUM(E3:E' . ($rowCount - 1) . ')');
                $event->sheet->setCellValue($totalDebetMutasi, '=SUM(F3:F' . ($rowCount - 1) . ')');
                $event->sheet->setCellValue($totalKreditMutasi, '=SUM(G3:G' . ($rowCount - 1) . ')');
                $event->sheet->setCellValue($totalDebetSaldoAkhir, '=SUM(H3:H' . ($rowCount - 1) . ')');
                $event->sheet->setCellValue($totalKreditSaldoAkhir, '=SUM(I3:I' . ($rowCount - 1) . ')');

                // Merge the total row across columns
                $event->sheet->mergeCells('A' . $rowCount . ':C' . $rowCount); // Merge columns A, B, C for the total row
                $event->sheet->setCellValue('A' . $rowCount, 'Total'); // Add "Total" label

                // Style the total row
                $event->sheet
                    ->getStyle('A' . $rowCount . ':J' . $rowCount)
                    ->getFont()
                    ->setBold(true);
                $event->sheet
                    ->getStyle('A' . $rowCount . ':J' . $rowCount)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
