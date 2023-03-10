<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengambilanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengambilan_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventori_id');
            $table->integer('jumlah');
            $table->string('tanggal');
            $table->string('keterangan');
            $table->foreign('inventori_id')->references('id')->on('inventories')->onDelete('cascade');
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
        Schema::dropIfExists('pengambilan_barangs');
    }
}
