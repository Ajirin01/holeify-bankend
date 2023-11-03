<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earning_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('worker_name');
            $table->string('earning');
            $table->string('status');
            $table->integer('worker_id');
            $table->foreign('worker_id')->unsigned()->references('id')->on('workers')->onDelete('cascade');
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
        Schema::dropIfExists('earning_wallets');
    }
};
