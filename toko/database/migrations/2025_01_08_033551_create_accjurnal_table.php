<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccjurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accjurnal', function (Blueprint $table) {
            $table->id();
            $table->integer('toko_id')->default(1);
            $table->integer('suplier_id')->nullable();
            $table->integer('pelanggan_id')->nullable();
            $table->string('tipe');
            $table->integer('urut');
            $table->integer('induk')->default(0);
            $table->string('invoice');
            $table->date('tanggal');
            $table->string('kode_acc');
            $table->string('kode_lawan');
            $table->string('keterangan')->nullable();
            $table->decimal('jumlah', 19, 2)->default(0.00);
            $table->string('posisi');
            $table->decimal('debet', 19, 2)->default(0.00);
            $table->decimal('kredit', 19, 2)->default(0.00);
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
        Schema::dropIfExists('accjurnal');
    }
}
