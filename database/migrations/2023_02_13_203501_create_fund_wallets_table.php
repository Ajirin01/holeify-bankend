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
        Schema::create('fund_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name');
            $table->string('fund');
            $table->string('status');
            $table->integer('requester_id');
            $table->foreign('requester_id')->unsigned()->references('id')->on('requesters')->onDelete('cascade');
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
        Schema::dropIfExists('fund_wallets');
    }
};
