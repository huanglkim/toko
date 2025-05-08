<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSatuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan');
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            ['nama_satuan' => 'PCS', 'created_at' => now()],
            ['nama_satuan' => 'KG', 'created_at' => now()],
            ['nama_satuan' => 'BOX', 'created_at' => now()],
            ['nama_satuan' => 'PACK', 'created_at' => now()],
            ['nama_satuan' => 'METER', 'created_at' => now()],
            ['nama_satuan' => 'LITER', 'created_at' => now()],
        ];
        DB::table('satuan')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('satuan');
    }
}
