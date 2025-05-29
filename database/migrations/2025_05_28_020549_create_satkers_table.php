<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('satkers', function (Blueprint $table) {
            $table->string('kode_satker')->primary();
            $table->string('nama_satker');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satkers');
    }
};