<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('nama_marketplace');
            $table->decimal('biaya_adm1', 8, 2)->default(0);
            $table->decimal('biaya_adm2', 8, 2)->default(0);
            $table->integer('biaya_lainrp')->default(0);
            $table->decimal('biaya_ongkir_persen', 5, 2)->default(0);
            $table->integer('biaya_ongkir_rp')->default(0);
            $table->integer('biaya_max_ongkir_rp')->default(0);
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
        Schema::dropIfExists('marketplace');
    }
}
