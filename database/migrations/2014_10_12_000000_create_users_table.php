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
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('adress')->nullable();
                $table->string('profile_image')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['user', 'merchant', 'admin', 'cs'])->default('user');
                $table->boolean('status')->nullable();
                $table->text('jwt_token')->nullable();
                $table->rememberToken()->nullable();
                $table->bigInteger('kelurahan_id')->unsigned()->nullable();
                $table->bigInteger('kecamatan_id')->unsigned()->nullable();
                $table->bigInteger('kabupaten_id')->unsigned()->nullable();
                $table->bigInteger('provinsi_id')->unsigned()->nullable();
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
