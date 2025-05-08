<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('induk');
            $table->string('nama');
            $table->string('link');
            $table->string('icon')->default('far fa-circle nav-icon');
            $table->timestamps();
            $table->softDeletes();
        });
        $data = [
            [
                'induk' => 'DataUsers',
                'nama' => 'DATA KARYAWAN',
                'link' => 'datauser',
                'icon' => 'far fa-circle nav-icon',
                'created_at' => now()
            ],
        ];
        DB::table('menus')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
