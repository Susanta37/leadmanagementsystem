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
        Schema::table('leads', function (Blueprint $table) {

            $table->string('lead_type')->nullable()->after('status');
            $table->string('voice_recording')->nullable()->after('lead_type');
            $table->boolean('is_personal_lead')->nullable()->after('status')->default(true);
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_type', 'voice_recording', 'is_personal_lead']);
        });
    }
};
