<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('last_location_update')->nullable()->after('checkout_image');
            $table->boolean('is_within_geofence')->default(false)->after('last_location_update');
            $table->string('reason')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
         $table->dropColumn(['last_location_update', 'is_within_geofence', 'reason']);
        });
    }
};
