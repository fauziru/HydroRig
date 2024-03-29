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
        if (!Schema::hasTable('sensors')) {
            Schema::create('sensors', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->nullable()->default(null);
                $table->bigInteger('node_id')->unsigned()->nullable();
                $table->string('name_sensor')->nullable();
                $table->string('tipe')->nullable();
                $table->json('threshold')->nullable();
                $table->string('created_by');
                $table->string('updated_by')->nullable();
                $table->double('last_read')->nullable();
                $table->timestamp('last_read_time')->nullable();
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
        Schema::dropIfExists('sensors');
    }
}
