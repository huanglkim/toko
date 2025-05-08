<?php

namespace App\Exports;

use App\Models\Pbhd;
use App\Models\Pbdt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPembelianDetailExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal, $tanggal_akhir, $suplier_id;

    public function __construct($tanggal_awal, $tanggal_akhir, $suplier_id = null)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->suplier_id = $suplier_id;
    }

    public function collection()
    {
        // Ambil semua transaksi pembelian sesuai dengan tanggal dan supplier
        $pbhdData = Pbhd::whereBetween('tanggal', [
                Carbon::parse($this->tanggal_awal)->startOfDay(),
                Carbon::parse($this->tanggal_akhir)->endOfDay()
            ])
            ->when($this->suplier_id, function ($query) {
                return $query->where('suplier_id', $this->suplier_id);
            })
            ->get();

        // Kumpulkan semua detail pembelian (Pbdt) berdasarkan transaksi Pbhd
        $data = collect();

        foreach ($pbhdData as $pbhd) {
            $pbdtData = Pbdt::where('pbhd_id', $pbhd->id)->get();

            foreach ($pbdtData as $pbdt) {
                $data->push([
                    'No Invoice' => $pbhd->invoice,
                    'Supplier' => $pbhd->suplier->nama,
                    'Tanggal' => TanggalIndo($pbhd->tanggal),
                    'Nama Item' => $pbdt->barang->nama_barang,
                    'Qty' => $pbdt->qty,
                    'Harga Bruto' => $pbdt->harga_bruto,
                    'Potongan' => $pbdt->total_pot,
                    'Harga Netto' => $pbdt->harga_netto,
                    'Total' => $pbdt->total_harga_netto,  
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Supplier',
            'Tanggal',
            'Nama Item',
            'Qty',
            'Harga Bruto',
            'Potongan',
            'Harga Netto',
            'Total',
        ];
    }
}
