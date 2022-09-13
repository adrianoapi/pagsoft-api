<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('description', 255)->nullable(true);
            $table->string('link')->nullable(false);
            $table->integer('limit')->nullable(true);
            $table->date('date')->nullable(true);
            $table->time('time')->nullable(true);
            $table->boolean('every_day')->default(false);
            $table->boolean('every_time')->default(false);
            $table->integer('executed')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_jobs');
    }
}
