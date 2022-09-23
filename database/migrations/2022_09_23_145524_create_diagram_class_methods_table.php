<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagramClassMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagram_class_methods', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('diagram_class_id');
            $table->string('name')->nullable(true);
            $table->string('type')->nullable(true);
            $table->string('visibility')->nullable(true);
            $table->timestamps();

            $table->foreign('diagram_class_id')->references('id')->on('diagram_classes')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagram_class_methods');
    }
}
