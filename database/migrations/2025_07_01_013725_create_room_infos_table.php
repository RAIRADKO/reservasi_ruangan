<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('room_infos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan');
            $table->text('deskripsi');
            $table->integer('kapasitas');
            $table->text('fasilitas');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_infos');
    }
};