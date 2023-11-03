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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->integer('reward');
            $table->integer('category_id');
            $table->foreign('category_id')->unsigned()->references('id')->on('categories')->onDelete('cascade');
            $table->integer('requester_id');
            $table->foreign('requester_id')->unsigned()->references('id')->on('requesters')->onDelete('cascade');
            $table->integer('total_need');
            $table->integer('total_done')->nullable();
            $table->string('link');
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
        Schema::dropIfExists('tasks');
    }
};
