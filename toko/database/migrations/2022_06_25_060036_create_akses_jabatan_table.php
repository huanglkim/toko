<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAksesJabatanTable extends Migration
{

    public function up()
    {
        Schema::create('akses_jabatan', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->integer('akses_id');
            $table->timestamps();
        });
        $data = [
            [
                'role_id' => 1,
                'akses_id' => 1,
                'created_at' => now()
            ],
        ];
        DB::table('akses_jabatan')->insert($data);
    }


    public function down()
    {
        Schema::dropIfExists('akses_jabatan');
    }
}
