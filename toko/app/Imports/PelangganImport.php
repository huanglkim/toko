<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class PelangganImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnError
{
    use Importable;
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
    public function model(array $row)
    {
        $wa = $row['wa'];
        $pelanggan = Pelanggan::where('wa', $wa)->first();
        if ($row['nama'] !== null) {
            if (!$pelanggan) {
                return new Pelanggan([
                    'kode' => $row['kode'] == "" ? kode(1) : $row['kode'],
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'] == "" ? 'UNKNOWN' : $row['alamat'],
                    'kota' => $row['kota'] == "" ? 'UNKNOWN' : $row['kota'],
                    'wa' => $row['wa'],
                ]);
            } else {
                $input = [
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'] == "" ? 'UNKNOWN' : $row['alamat'],
                    'kota' => $row['kota'] == "" ? 'UNKNOWN' : $row['kota'],
                ];
                $pelanggan->update($input);
            }
        }
    }
    public function rules(): array
    {
        return [
            'kode' => Rule::unique('pelanggan', 'kode'), // Table name, field in your db
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
