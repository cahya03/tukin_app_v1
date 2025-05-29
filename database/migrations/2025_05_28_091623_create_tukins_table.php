<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tukin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_tukin')->nullable();
            $table->string('id_proses')->nullable();
            $table->enum('tni_pns', ['TNI', 'PNS'])->nullable();
            $table->string('nomor_tukin')->nullable();
            $table->string('kdsatker')->nullable();
            $table->string('nip')->nullable();
            $table->string('nama_pegawai')->nullable();
            $table->string('jenis_pegawai')->nullable();
            $table->string('jenis_sk')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->string('grade')->nullable();
            $table->string('jenis_tukin')->nullable();
            $table->double('kotor')->nullable();
            $table->double('potongan')->nullable();
            $table->double('bersih')->nullable();
            $table->double('pajak')->nullable();
            $table->double('tunj_pajak')->nullable();
            $table->double('bersih_2')->nullable();
            $table->string('kdbankspan')->nullable();
            $table->string('rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('bulan_awal')->nullable();
            $table->string('tahun_awal')->nullable();
            $table->string('bulan_akhir')->nullable();
            $table->string('tahun_akhir')->nullable();
            $table->integer('kali_pembayaran')->nullable();
            $table->string('nomor_tukin_lama')->nullable();
            $table->string('nomor_tukin_baru')->nullable();
            $table->unsignedBigInteger('header_id')->nullable();
            $table->foreign('header_id')
                ->references('id')
                ->on('headers')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tukin');
    }
};
