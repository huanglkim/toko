<?php

namespace App\Imports;

use App\Models\Jasa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class JasaImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnError
{
    use Importable;
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
    public function model(array $row)
    {
        return new Jasa([
            'kode' => $row['kode'] == "" ? kode(13) : $row['kode'],
            'nama' => $row['nama'],
            'harga1' => $row['harga1'] == "" ? 0 : $row['harga1'],
            'harga2' => $row['harga2'] == "" ? 0 : $row['harga2'],
            'keterangan' => $row['keterangan'],
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => Rule::unique('jasa', 'kode'), // Table name, field in your db
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
