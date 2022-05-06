<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToConfigCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('show_id');
            $table->boolean('show_image');
            $table->boolean('show_title');
            $table->boolean('show_description');
            $table->boolean('show_release');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('show_id');
            $table->dropColumn('show_image');
            $table->dropColumn('show_title');
            $table->dropColumn('show_description');
            $table->dropColumn('show_release');
        });
    }
}
