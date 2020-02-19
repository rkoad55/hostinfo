<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alexa')->nullable();
            $table->text('dns')->nullable();
            $table->text('ip')->nullable();
            $table->string('isp')->nullable();
            $table->string('org')->nullable();
            $table->string('mxIp')->nullable();
            $table->string('mxIsp')->nullable();
            $table->string('mxOrg')->nullable();
            $table->integer('domain_id')->nullable();
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
        Schema::dropIfExists('histories');
    }
}
