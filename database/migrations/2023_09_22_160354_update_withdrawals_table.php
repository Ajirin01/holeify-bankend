<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Change 'paid' column to 'status' and set 'pending' as the default value
            $table->string('status')->default('pending');
            
            // Add a new 'done_tasks' column as longText
            $table->longText('done_tasks')->after('status');
        });
    }

    public function down()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->dropColumn('done_tasks');
            $table->boolean('paid')->default(false)->change();
        });
    }
}
