<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeluargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('nama_keluarga')->nullable();
            $table->string('status_keluarga')->nullable();
            $table->string('born_place')->nullable();
            $table->date('born_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('nation')->nullable();
            $table->string('religion')->nullable();
            $table->string('ktp_number')->nullable();
            $table->string('married_status')->nullable();
            $table->string('job')->nullable();
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
        Schema::dropIfExists('keluargas');
    }
}
