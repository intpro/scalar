<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floats', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('entity_name');
            $table->integer('entity_id');
            $table->string('name');
            $table->float('value', 12, 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('floats');
    }
}
