<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbdtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbdt', function (Blueprint $table) {
            $table->id();
            $table->integer('pbhd_id');
            $table->integer('popbdt_id')->nullable();
            $table->integer('barang_id');
            $table->integer('gudang_id')->default(1);
            $table->decimal('qty', 17, 2)->default(0.00);
            $table->string('mutasi')->default('IN');
            $table->string('tipe')->default('PB');
            $table->string('invoice');
            $table->decimal('harga_bruto', 19, 0)->default(0);
            $table->decimal('potpersen', 19, 0)->default(0);
            $table->decimal('potrp', 19, 0)->default(0);
            $table->decimal('total_pot', 19, 0)->default(0);
            $table->decimal('harga_netto', 19, 0)->default(0);
            $table->decimal('ppn', 19, 0)->default(0);
            $table->decimal('hpp', 19, 0)->default(1);
            $table->decimal('totalppn', 19, 0)->default(0);
            $table->decimal('totalhpp', 19, 0)->default(1);
            $table->decimal('total_harga_netto', 19, 0)->default(0);
            $table->integer('user_id')->default(1);
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
        Schema::dropIfExists('pbdt');
    }
}
