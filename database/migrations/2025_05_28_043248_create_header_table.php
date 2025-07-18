<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_header')->nullable();
            $table->string('deskripsi_header')->nullable();
            $table->string('kode_satker');
            $table->date('tanggal')->nullable();
            $table->string('file_tni_path')->nullable();  
            $table->string('file_pns_path')->nullable();  
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Correct foreign key definition
            $table->foreign('kode_satker')
                  ->references('kode_satker')
                  ->on('satkers') // Note: plural table name
                  ->onDelete('restrict');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users') // Note: plural table name
                  ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headers');
    }
};