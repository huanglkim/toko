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

class NeracaSaldoTahunanExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;

        $validator = Validator::make(
            ['tahun' => $tahun],
            [
                'tahun' => 'required|date_format:Y',
            ]
        );

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Invalid parameters: ' . implode(', ', $validator->errors()->all()));
        }
    }

    public function collection()
    {
        $tahun = $this->tahun;
        $toko_id = 1;

        // Get accmutasi data for the specified tahun
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

            // Saldo awal (Sum mutasi tahun sebelumnya)
            $saldoSebelumnya = Acc_sa::where('kode_acc', $kode_acc)
                ->where('tahun', $tahun)
                ->where('toko_id', $toko_id)
                ->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')
                ->first();

            // Mutasi dari tahun sebelumnya (2024, 2023, dst)
            $mutasiSebelumnya = Accmutasi::where('kode_acc', $kode_acc)
                ->where('tahun', '<', $tahun)
                ->where('toko_id', $toko_id)
                ->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')
                ->first();

            $debetAwal = ($saldoSebelumnya->total_debet ?? 0) + ($mutasiSebelumnya->total_debet ?? 0);
            $kreditAwal = ($saldoSebelumnya->total_kredit ?? 0) + ($mutasiSebelumnya->total_kredit ?? 0);

            // Mutasi tahun berjalan (2025)
            $mutasiSekarang = Accmutasi::where('kode_acc', $kode_acc)
                ->where('tahun', $tahun)
                ->where('toko_id', $toko_id)
                ->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')
                ->first();

            $debetMutasi = $mutasiSekarang->total_debet ?? 0;
            $kreditMutasi = $mutasiSekarang->total_kredit ?? 0;

            // Calculate Saldo Akhir Debet dan Kredit
            $saldoAkhirDebet = $debetAwal + $debetMutasi;
            $saldoAkhirKredit = $kreditAwal + $kreditMutasi;

            // Update totals
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

        // Add totals row after the loop ends
        $data[] = [
            'kode_acc' => 'Total',
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
                $event->sheet->mergeCells('A1:A2');
                $event->sheet->mergeCells('B1:B2');
                $event->sheet->mergeCells('C1:C2');
                $event->sheet->mergeCells('D1:E1');
                $event->sheet->mergeCells('F1:G1');
                $event->sheet->mergeCells('H1:I1');
                $event->sheet->setCellValue('D1', 'Saldo Awal');
                $event->sheet->setCellValue('F1', 'Mutasi');
                $event->sheet->setCellValue('H1', 'Saldo Akhir');

                $event->sheet->getStyle('A1:I1')->getFont()->setBold(true);
                $event->sheet->getStyle('A2:I2')->getFont()->setBold(true);

                $event->sheet
                    ->getStyle('A1:I' . $event->sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

                $event->sheet->mergeCells('A' . $rowCount . ':C' . $rowCount);
                $event->sheet->setCellValue('A' . $rowCount, 'Total');

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
