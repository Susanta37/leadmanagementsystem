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
        $table->enum('target_type', ['individual', 'all'])->default('individual')->after('team_lead_id');
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('target_type');
    });
}

};
