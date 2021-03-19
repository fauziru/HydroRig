<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sensor')) {
            Schema::create('sensor', function (Blueprint $table) {
                $table->id();
                $table->string('name_sensor')->nullable();
                $table->decimal('min_nutrisi', 5)->nullable();
                $table->boolean('konektivitas')->nullable()->default(false);
                $table->timestamp('last_read')->nullable();
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
        Schema::dropIfExists('sensor');
    }
}
