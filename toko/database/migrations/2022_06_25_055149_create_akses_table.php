<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAksesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses', function (Blueprint $table) {
            $table->id();
            $table->string('induk');
            $table->string('nama_akses');
            $table->timestamps();
        });
        $data = [
            [
                'induk' => 'Admin',
                'nama_akses' => 'Hapus Data',
                'created_at' => now()
            ],
            [
                'induk' => 'Penjualan',
                'nama_akses' => 'Edit Harga/Diskon',
                'created_at' => now()
            ],
            [
                'induk' => 'Penjualan',
                'nama_akses' => 'Edit Penjualan',
                'created_at' => now()
            ],
            [
                'induk' => 'Pembelian',
                'nama_akses' => 'Edit Pembelian',
                'created_at' => now()
            ],
            [
                'induk' => 'Master Data',
                'nama_akses' => 'Edit Pelanggan/suplier',
                'created_at' => now()
            ],
            [
                'induk' => 'Master Data',
                'nama_akses' => 'Edit Barang',
                'created_at' => now()
            ],

        ];
        DB::table('akses')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akses');
    }
}
