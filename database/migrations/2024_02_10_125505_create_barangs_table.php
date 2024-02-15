<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
			$table->string('nama');
			$table->unsignedBigInteger('harga');
            $table->unsignedBigInteger('kategori_id');
			$table->foreign('kategori_id')->references('id')->on('kategoris');
			$table->unsignedInteger('jumlah');
			$table->string('foto');
            $table->longText('deskripsi');
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
        Schema::dropIfExists('barangs');
    }
};
