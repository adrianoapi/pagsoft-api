<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagramItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagram_items', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('diagram_id');
            $table->string('category')->nullable(true);
            $table->integer('key')->nullable(true);
            $table->integer('parent')->nullable(true);
            $table->string('text')->nullable(true);
            $table->string('brush')->nullable(true);
            $table->string('dir')->nullable(true);
            $table->string('loc')->nullable(true);
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
        Schema::dropIfExists('diagram_items');
    }
}
