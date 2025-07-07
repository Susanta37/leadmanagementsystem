<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        // Drop foreign key constraint first
        $table->dropForeign(['employee_id']);

        // Then drop the column
        $table->dropColumn('employee_id');
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        // Add the column back
        $table->unsignedBigInteger('employee_id')->nullable();

        // Re-apply foreign key if needed
        $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
    });
}


};
