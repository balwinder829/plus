<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('foryear_id');
            $table->integer('invoice_type_id');
            $table->integer('paid');
            $table->string('file_link');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->integer('credit');
            $table->timestamp('insertdate');
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
        Schema::dropIfExists('users_invoices');
    }
}
