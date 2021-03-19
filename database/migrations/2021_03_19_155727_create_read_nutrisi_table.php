<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadNutrisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('read_nutrisi')) {
            Schema::create('read_nutrisi', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('sensor_id')->unsigned()->nullable();
                $table->decimal('read_nutrisi', 5, 0)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('read_nutrisi');
    }
}
