<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagramClassMethodParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagram_class_method_parameters', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('diagram_class_method_id');
            $table->string('name')->nullable(true);
            $table->string('type')->nullable(true);
            $table->timestamps();

            $table->foreign('diagram_class_method_id')->references('id')->on('diagram_class_methods')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagram_class_method_parameters');
    }
}
