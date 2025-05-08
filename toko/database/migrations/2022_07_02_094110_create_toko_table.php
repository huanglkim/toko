<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTokoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('kode')->nullable();
            $table->string('nama_toko')->unique();
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('npwp')->nullable();
            $table->string('telp')->nullable();
            $table->string('wa')->nullable();
            $table->string('logo')->default('images/logo.png');
            $table->integer('acc_periode_id')->default(1);
            $table->timestamps();
        });
        $data = [
            [
                'kode' => 'wu1',
                'nama_toko' => 'WU BANGUNAN',
                'alamat' => 'Jl. Ki Hajar Dewantara No.169, Katerungan, Katrungan, Kec. Krian, Kabupaten Sidoarjo, Jawa Timur 61262',
                'wa' => '6281333333206',
                'created_at' => now()
            ],
        ];
        DB::table('toko')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toko');
    }
}
