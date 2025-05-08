<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('kota');
            $table->string('wa')->nullable();
            $table->integer('group')->default(1);
            $table->string('password')->default('xxx');
            $table->integer('poin')->default(0);
            $table->decimal('dp', 17, 0)->default(0.00);
            $table->decimal('piutang', 17, 0)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            [
                'kode' => 'umum',
                'nama' => 'umum',
                'alamat' => '-',
                'kota' => '-',
                'password' => 'xxx',
                'created_at' => now()
            ],
        ];
        DB::table('pelanggan')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
}
