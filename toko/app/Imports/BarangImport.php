<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use App\Models\Barang;
use App\Models\Suplier;
use App\Models\Jenisbarang;
use App\Models\Merkbarang;
use App\Models\Satuan;
use App\Models\Barangin;

class BarangImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnError
{
    use Importable;
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }

    public function model(array $row)
    {

        $kode = $row['kode'];
        $barcode = $row['barcode'];
        $kode_internal = $row['kode_internal'];
        $nama_barang = $row['nama_barang'];
        if ($nama_barang != null) {

            $barang = Barang::where('kode', $kode)
                ->orwhere('barcode', $barcode)
                ->orwhere('kode_internal', $kode_internal)
                ->orwhere('nama_barang', $nama_barang)
                ->first();
            $satuan = $row['satuan'] ?? "NN";
            $datasatuan = Satuan::where('nama_satuan', $satuan)->first();
            if (!$datasatuan) {
                $datasatuan = Satuan::create(['nama_satuan' => $satuan]);
            }
            $satuan_id = $datasatuan->id;

            $merkbarang = $row['merkbarang'] ?? 'TANPA MERK';
            $datamerkbarang = Merkbarang::where('nama_merkbarang', $merkbarang)->first();
            if (!$datamerkbarang) {
                $datamerkbarang = Merkbarang::create(['nama_merkbarang' => $merkbarang]);
            }
            $merkbarang_id = $datamerkbarang->id;

            $jenisbarang = $row['jenisbarang'] ?? 'TANPA JENIS';
            $datajenisbarang = Jenisbarang::where('nama_jenisbarang', $jenisbarang)->first();
            if (!$datajenisbarang) {
                $datajenisbarang = Jenisbarang::create(['nama_jenisbarang' => $jenisbarang]);
            }
            $jenisbarang_id = $datajenisbarang->id;

            $suplier = $row['suplier'] ?? 'TANPA SUPLIER';
            $datasuplier = Suplier::where('nama', $suplier)->first();
            if (!$datasuplier) {
                $datasuplier = Suplier::create(['kode' => kode(2), 'nama' => $suplier]);
            }
            $suplier_id = $datasuplier->id;
            if (!$barang) {
                $inputbaru = [
                    'kode' => $row['kode'] == "" ? kode(12) : $row['kode'],
                    'kode_internal' => $row['kode_internal'] == "" ? kode(12) : $row['kode_internal'],
                    'barcode' => $row['barcode'] == "" ? kode(12) : $row['barcode'],
                    'nama_barang' => $row['nama_barang'],
                    'satuan_id' => $satuan_id,
                    'merkbarang_id' => $merkbarang_id,
                    'jenisbarang_id' => $jenisbarang_id,
                    'harga_jual_dasar1' => $row['harga_jual_dasar1'] == "" ? 0 : $row['harga_jual_dasar1'],
                    'harga_jual_dasar2' => $row['harga_jual_dasar2'] == "" ? 0 : $row['harga_jual_dasar2'],
                    'suplier_id' => $suplier_id,
                    'suplierterakhir_id' => $suplier_id,
                    'minimum' => $row['minimum'] == "" ? 0 : $row['minimum'],
                    'keterangan' => $row['keterangan'] == "" ? '' : $row['keterangan'],
                ];
                $barang = Barang::create($inputbaru);
                if ($row['stok'] > 0) {
                    $totalhpp = $row['harga_beli'] * $row['stok'];
                    $inputbarangin = [
                        'tanggal' => now(),
                        'barang_id' => $barang->id,
                        'qty' => $row['stok'],
                        'tipe' => 'SA',
                        'invoice' => 'SA' . date('d/m/Y'),
                        'gudang_id' => 1,
                        'hpp' => $row['harga_beli'] == "" ? 0 : $row['harga_beli'],
                        'totalhpp' => $totalhpp,
                        'harga_bruto' => $row['harga_beli'] == "" ? 0 : $row['harga_beli'],
                        'harga_netto' => $row['harga_beli'] == "" ? 0 : $row['harga_beli'],
                        'user_id' => Auth()->User()->id,
                    ];
                    Barangin::create($inputbarangin);
                }
            } else {
                $input = [
                    'nama_barang' => $row['nama_barang'],
                    'satuan_id' => $satuan_id,
                    'merkbarang_id' => $merkbarang_id,
                    'jenisbarang_id' => $jenisbarang_id,
                    'harga_jual_dasar1' => $row['harga_jual_dasar1'] == "" ? 0 : $row['harga_jual_dasar1'],
                    'harga_jual_dasar2' => $row['harga_jual_dasar2'] == "" ? 0 : $row['harga_jual_dasar2'],
                    'suplier_id' => $suplier_id,
                    'minimum' => $row['minimum'] == "" ? 0 : $row['minimum'],
                    'keterangan' => $row['keterangan'] == "" ? '' : $row['keterangan'],
                ];
                $barang->update($input);
            }
        }
    }

    public function rules(): array
    {
        return [
            'kode' => Rule::unique('barang', 'kode'), // Table name, field in your db
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode.unique' => 'Kode SAMA',
        ];
    }


    public function chunkSize(): int
    {
        return 1000;
    }
}
