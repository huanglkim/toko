<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGlobalconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globalconfig', function (Blueprint $table) {
            $table->id();
            $table->string('nama_config');
            $table->string('data_config');
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            ['nama_config' => 'ppnpb', 'data_config' => '11', 'created_at' => now()],
            ['nama_config' => 'ppnpj', 'data_config' => '11', 'created_at' => now()],
            ['nama_config' => 'publicfolder', 'data_config' => '/wu/public/', 'created_at' => now()],
        ];
        DB::table('globalconfig')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('globalconfig');
    }
}
