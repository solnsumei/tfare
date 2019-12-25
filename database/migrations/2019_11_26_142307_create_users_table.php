<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->boolean('isAdmin')->index();
            $table->string('api_token')->unique()->nullable();
            $table->unsignedBigInteger('role_id')->index()->nullable();
            $table->unsignedBigInteger('company_id')->index()->nullable();
            $table->unsignedBigInteger('terminal_id')->index()->nullable();
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
        Schema::dropIfExists('users');
    }
}
