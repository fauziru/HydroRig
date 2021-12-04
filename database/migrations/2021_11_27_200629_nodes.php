<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Nodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('nodes')) {
            Schema::create('nodes', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->nullable()->default(null);
                $table->string('name_node')->nullable();
                $table->double('lat')->nullable();
                $table->double('lng')->nullable();
                $table->boolean('konektivitas')->nullable()->default(FALSE);
                $table->tinyInteger('status')->nullable()->default(FALSE);
                $table->string('created_by');
                $table->string('updated_by')->nullable();
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
        //
        Schema::dropIfExists('nodes');
    }
}
