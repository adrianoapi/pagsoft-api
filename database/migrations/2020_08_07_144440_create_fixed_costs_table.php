<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ledger_group_id');
            $table->unsignedBigInteger('transition_type_id');
            $table->string('description');
            $table->date('entry_date');
            $table->decimal('amount', 10, 2);
            $table->integer('recurrent')->default(0);  # dia do mês para recorrer ou 0 para não recorrer 
            $table->boolean('notify')->default(FALSE); # enviará e-mail ou sms
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('ledger_group_id')->references('id')->on('ledger_groups')->onDelete('CASCADE');
            $table->foreign('transition_type_id')->references('id')->on('transition_types')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_costs');
    }
}
