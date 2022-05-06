<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ledger_group_id')->nullable(true);
            $table->string('title');
            $table->text('description')->nullable(true);
            $table->timestamps();
        });

        Schema::table('ledger_groups', function (Blueprint $table) 
        {
            $table->foreign('ledger_group_id')->references('id')->on('ledger_groups')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_groups');
    }
}
