<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjhdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pjhd', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('status')->default(1);
            $table->integer('status_piutang')->default(1);
            $table->integer('dopjhd_id')->nullable();
            $table->string('invoice')->unique();
            $table->string('tipe')->default('PJ');
            $table->date('tanggal');
            $table->integer('pelanggan_id')->default(1);
            $table->string('nama_pel')->nullable();
            $table->integer('user_id');
            $table->integer('useredit_id')->nullable();
            $table->date('tanggal_kirim')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jenisppn')->default('include');
            $table->integer('persenpajak')->default(11);
            $table->decimal('hpp', 17, 2)->default(0.00);
            $table->decimal('ppn', 17, 2)->default(0.00);
            $table->decimal('dpp', 17, 2)->default(0.00);
            $table->decimal('total', 17, 2)->default(0.00);
            $table->decimal('potongan', 17, 2)->default(0.00);
            $table->decimal('dp', 17, 2)->default(0.00);
            $table->decimal('kas', 17, 2)->default(0.00);
            $table->decimal('bank', 17, 2)->default(0.00);
            $table->decimal('piutang', 17, 2)->default(0.00);
            $table->string('kode_acc_hpp')->default('1-1301');
            $table->string('kode_acc_ppn')->default('2-4110');
            $table->string('kode_acc_dpp')->default('4-1000');
            $table->string('kode_acc_potongan')->default('4-1300');
            $table->string('kode_acc_dp')->default('1-9100');
            $table->string('kode_acc_kas')->default('1-1111');
            $table->string('kode_acc_bank')->default('1-1120');
            $table->string('kode_acc_piutang')->default('2-1101');
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
        Schema::dropIfExists('pjhd');
    }
}
