<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPjhdmplTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pjhd', function (Blueprint $table) {
            $table->integer('marketplace_id')->nullable();
            $table->string('tipe_admin_mpl')->default('include');
            $table->decimal('admin_mpl', 17, 2)->default(0);
            $table->decimal('admin_lain', 17, 2)->default(0);
            $table->string('kode_acc_admin_mpl')->default('5-1301');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
