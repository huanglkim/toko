<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan');
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            ['nama_jabatan' => 'Administrator', 'created_at' => now()],
            ['nama_jabatan' => 'Supervisor', 'created_at' => now()],
            ['nama_jabatan' => 'Kasir', 'created_at' => now()],
            ['nama_jabatan' => 'Karyawan', 'created_at' => now()],
            ['nama_jabatan' => 'MD', 'created_at' => now()],
        ];
        DB::table('role')->insert($data);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
