<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->nullable()->default(null);
                $table->string('name_user');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('adress')->nullable();
                $table->string('profile_image')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamp('last_seen')->nullable();
                $table->string('password');
                $table->bigInteger('role_id')->unsigned();
                $table->boolean('is_online')->nullable();
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
        Schema::dropIfExists('users');
    }
}
