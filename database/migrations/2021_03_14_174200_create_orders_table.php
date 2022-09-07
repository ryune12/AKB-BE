<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservasi')->constrained('reservasis');
            $table->foreignId('id_karyawan')->nullable()->constrained('users');

            $table->double('total')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('total_item')->nullable();
            $table->double('tax')->nullable();
            $table->double('services')->nullable();
            $table->string('jenis_pembayaran')->nullable();
            $table->foreignId('id_kartu')->nullable()->constrained('card_infos', 'no_kartu');
            $table->string('kode_verifikasi')->nullable();
            $table->string('status_pembayaran')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
