<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccjurnalkhususTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accjurnalkhusus', function (Blueprint $table) {
            $table->id();
            $table->integer('toko_id')->default(1);
            $table->string('tipe')->default('KK');
            $table->integer('karyawan_id')->nullable();
            $table->string('invoice');
            $table->date('tanggal');
            $table->string('kode_acc');
            $table->string('posisi')->default('K');
            $table->string('kode_lawan');
            $table->decimal('jumlah', 19, 2);
            $table->string('keterangan')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('accjurnalkhusus');
    }
}
