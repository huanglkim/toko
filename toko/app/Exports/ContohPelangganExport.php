<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ContohPelangganExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pelanggan::select('kode', 'nama', 'wa', 'alamat', 'kota')->get();
    }
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Wa', 'Alamat', 'Kota'];
    }
}
