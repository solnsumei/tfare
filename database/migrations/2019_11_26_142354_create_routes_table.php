<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('source_id')->index();
            $table->foreign('source_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('destination_id')->index();
            $table->foreign('destination_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('terminal_id')->index();
            $table->foreign('terminal_id')->references('id')->on('terminals')->onDelete('cascade');
            $table->json('fares');
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
        Schema::dropIfExists('routes');
    }
}
