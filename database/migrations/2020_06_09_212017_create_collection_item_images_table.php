<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_item_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('collection_item_id');
            $table->longText('image');
            $table->string('type');
            $table->integer('size');
            $table->timestamps();

            $table->foreign('collection_item_id')->references('id')->on('collection_items')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_item_images');
    }
}
