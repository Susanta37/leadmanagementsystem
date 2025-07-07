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
    Schema::table('leads', function (Blueprint $table) {
        $table->string('reason')->nullable()->after('status'); // adjust 'status' if you want to place it after a different column
    });
}

public function down()
{
    Schema::table('leads', function (Blueprint $table) {
        $table->dropColumn('reason');
    });
}

};
