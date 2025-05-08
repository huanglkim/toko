<?php

namespace App\Imports;

use App\Models\PelKendaraan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use App\Models\MerkKendaraan;

class PelKendaraanImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnError
{
    use Importable;
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
    public function model(array $row)
    {
        if ($row['bbm'] == "SOLAR") {
            $bbm = 2;
        } else if ($row['bbm'] == "LISTRIK") {
            $bbm = 3;
        } else {
            $bbm = 1;
        }
        $MerkKendaraan = MerkKendaraan::where('nama', $row['merk_kendaraan'])->first();
        if (empty($MerkKendaraan)) {
            $MerkKendaraan = MerkKendaraan::create(['nama' => $row['merk_kendaraan']]);
        }
        return new PelKendaraan([
            'plat_nomor' => $row['plat_nomor'],
            'noka' => $row['noka'],
            'nosin' => $row['nosin'],
            'merk_kendaraan_id' => $MerkKendaraan->id,
            'seri' => $row['seri'],
            'warna' => $row['warna'],
            'bbm' => $bbm,
        ]);
    }
    public function rules(): array
    {
        return [
            'pel_kendaraan' => Rule::unique('noka', 'plat_nomor'), // Table name, field in your db
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
