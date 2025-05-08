<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->integer('role_id');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('wa')->unique();
            $table->string('alamat');
            $table->string('kota')->nullable();
            $table->string('password');
            $table->string('rfid')->nullable();
            $table->string('foto')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::table('users')->insert(
            array(
                'role_id'   => 1,
                'nama'      => 'admin',
                'username'  => 'admin',
                'alamat'    => 'jombang',
                'kota'    => 'jombang',
                'password'  => '$2y$10$QdpOEJQMAEViMWF.xUbrZeTlXyjwj46/Xd6O3tP86d9k4tFSkrqvC',
                'wa'        => '6281212275212',
                'status'    => 1,
                'created_at'    => now(),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
