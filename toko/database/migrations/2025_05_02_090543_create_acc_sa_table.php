<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccSaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_sa', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('toko_id')->default(1);
            $table->string('kode_acc');
            $table->decimal('debet', 17, 2)->default(0.00);
            $table->decimal('kredit', 17, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acc_sa');
    }
}
