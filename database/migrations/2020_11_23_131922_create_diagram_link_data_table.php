<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagramLinkDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagram_link_data', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('diagram_id');
            $table->integer('from')->nullable(true);
            $table->integer('to')->nullable(true);
            $table->string('fromPort')->nullable(true);
            $table->string('toPort')->nullable(true);
            $table->boolean('visible')->default(false);
            $table->string('text')->nullable(true);
            $table->timestamps();

            $table->foreign('diagram_id')->references('id')->on('diagrams')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagram_link_data');
    }
}
