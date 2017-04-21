<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('node_ip');
            $table->string('node_ip4')->nullable();
            $table->string('node_ip6')->nullable();
            $table->integer('port');
            $table->string('docker');
            $table->text('config');
            $table->string('request_ip');
            $table->string('status');
            $table->longText('log')->nullable();
            $table->unsignedBigInteger('time');
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
        Schema::drop('jobs');
    }
}
