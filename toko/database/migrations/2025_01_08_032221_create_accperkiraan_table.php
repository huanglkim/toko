<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccperkiraanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accperkiraan', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->integer('toko_id')->default(1);
            $table->string('induk_acc')->nullable();
            $table->string('kode_acc')->unique();
            $table->string('nama_acc');
            $table->integer('kelompok');
            $table->string('tipe')->default('dt');
            $table->integer('kas')->default(0);
            $table->integer('bank')->default(0);
            $table->string('posisi')->default('D');
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
        Schema::dropIfExists('accperkiraan');
    }
}
