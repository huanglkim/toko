<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('kode')->unique();
            $table->string('kode_internal')->nullable();
            $table->string('barcode')->unique();
            $table->string('nama_barang')->unique();
            $table->integer('satuan_id')->default(1);
            $table->decimal('stok', 17, 0)->default(0);
            $table->integer('merkbarang_id')->default(1);
            $table->integer('jenisbarang_id')->default(1);
            $table->integer('tipe_harga')->default(1);
            $table->decimal('harga_jual_dasar1', 17, 0)->default(0);
            $table->decimal('harga_jual_dasar2', 17, 0)->default(0);
            $table->integer('ppn')->default(1);
            $table->decimal('harga_beli_terakhir', 17, 0)->default(0);
            $table->integer('suplier_id')->default(1);
            $table->integer('suplierterakhir_id')->default(1);
            $table->decimal('hpp_satuan', 17, 0)->default(0);
            $table->decimal('hpp_total', 17, 0)->default(0);
            $table->string('foto')->default('barang.jpg');
            $table->string('keterangan')->nullable();
            $table->integer('minimum')->default(0);
            $table->string('kode_acc')->default('1-1301');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
}
