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
        Schema::create('submitted_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->foreign('task_id')->unsigned()->references('id')->on('tasks')->onDelete('cascade');
            $table->integer('worker_id');
            $table->foreign('worker_id')->unsigned()->references('id')->on('workers')->onDelete('cascade');
            $table->integer('requester_id');
            $table->foreign('requester_id')->unsigned()->references('id')->on('requester')->onDelete('cascade');
            $table->string('reward');
            $table->longText('prove_photo');
            $table->string('status');
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
        Schema::dropIfExists('submitted_tasks');
    }
};
