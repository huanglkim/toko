<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateSuplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suplier', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('wa')->nullable();
            $table->string('telp')->nullable();
            $table->integer('group')->default(1);
            $table->decimal('dp', 17, 0)->default(0.00);
            $table->decimal('hutang', 17, 0)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            [
                'kode' => 'NON',
                'nama' => 'NONAME',
                'alamat' => '-',
                'kota' => '-',
                'created_at' => now()
            ],
        ];
        DB::table('suplier')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suplier');
    }
}
