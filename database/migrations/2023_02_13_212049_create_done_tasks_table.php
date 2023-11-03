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
        Schema::create('done_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('earning');
            $table->boolean('paid');
            $table->integer('worker_id');
            $table->foreign('worker_id')->unsigned()->references('id')->on('workers')->onDelete('cascade');
            $table->integer('task_id');
            $table->foreign('task_id')->unsigned()->references('id')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('done_tasks');
    }
};
