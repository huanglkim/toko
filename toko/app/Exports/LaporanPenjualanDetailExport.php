<?php

namespace App\Exports;

use App\Models\Pjhd;
use App\Models\Pjdt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPenjualanDetailExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal, $tanggal_akhir, $pelanggan_id;

    public function __construct($tanggal_awal, $tanggal_akhir, $pelanggan_id = null)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->pelanggan_id = $pelanggan_id;
    }

    public function collection()
    {
        // Ambil semua transaksi penjualan sesuai dengan tanggal dan supplier
        $pjhdData = Pjhd::whereBetween('tanggal', [
                Carbon::parse($this->tanggal_awal)->startOfDay(),
                Carbon::parse($this->tanggal_akhir)->endOfDay()
            ])
            ->when($this->pelanggan_id, function ($query) {
                return $query->where('pelanggan_id', $this->pelanggan_id);
            })
            ->get();

        // Kumpulkan semua detail penjualan (pjdt) berdasarkan transaksi pjhd
        $data = collect();

        foreach ($pjhdData as $pjhd) {
            $pjdtData = pjdt::where('pjhd_id', $pjhd->id)->get();

            foreach ($pjdtData as $pjdt) {
                $data->push([
                    'No Invoice' => $pjhd->invoice,
                    'Pelanggan' => $pjhd->pelanggan->nama,
                    'Tanggal' => TanggalIndo($pjhd->tanggal),
                    'Nama Item' => $pjdt->barang->nama_barang,
                    'Qty' => $pjdt->qty,
                    'Harga Bruto' => $pjdt->harga_bruto,
                    'Potongan' => $pjdt->total_pot,
                    'Harga Netto' => $pjdt->harga_netto,
                    'Total' => $pjdt->total_harga_netto,  
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Pelanggan',
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
