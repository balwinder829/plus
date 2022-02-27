<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlusUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plus_users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->bigInteger('mobile');
            $table->integer('rooms')->nullable();
            $table->string('smscode')->nullable();
            $table->timestamp('smsCodeExpriration')->nullable();
            $table->timestamp('insetTime')->nullable();
            $table->timestamp('lastupdate')->nullable();
            $table->integer('status')->nullable();
            $table->integer('taxon_status')->nullable();
            //$table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plus_users');
    }
}
