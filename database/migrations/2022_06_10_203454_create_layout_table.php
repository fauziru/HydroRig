<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('layouts')) {
            Schema::create('layouts', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->nullable()->default(null);
                $table->string('name_layout')->nullable();
                $table->string('file_name')->nullable();
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
        Schema::dropIfExists('layouts');
    }
}
