<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbhdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbhd', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('status')->default(1);
            $table->integer('status_hutang')->default(1);
            $table->integer('popbhd_id')->nullable();
            $table->string('invoice')->unique();
            $table->date('tanggal');
            $table->integer('suplier_id')->default(1);
            $table->integer('user_id');
            $table->integer('useredit_id')->nullable();
            $table->date('tanggal_kirim')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jenisppn')->default('include');
            $table->integer('persenpajak')->default(11);
            $table->decimal('ppn', 17, 2)->default(0.00);
            $table->string('kode_acc_ppn')->default('1-1410');
            $table->decimal('dpp', 17, 2)->default(0.00);
            $table->string('kode_acc_dpp')->default('1-1301');
            $table->decimal('total', 17, 2)->default(0.00);
            $table->decimal('potongan', 17, 2)->default(0.00);
            $table->string('kode_acc_potongan')->default('1-1303');
            $table->decimal('dp', 17, 2)->default(0.00);
            $table->string('kode_acc_dp')->default('1-9100');
            $table->decimal('kas', 17, 2)->default(0.00);
            $table->string('kode_acc_kas')->default('1-1111');
            $table->decimal('hutang', 17, 2)->default(0.00);
            $table->string('kode_acc_hutang')->default('2-1101');
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
        Schema::dropIfExists('pbhd');
    }
}
