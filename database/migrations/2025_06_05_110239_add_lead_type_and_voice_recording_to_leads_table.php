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
            if (!Schema::hasColumn('leads', 'turnover_amount')) {
                
            $table->decimal('turnover_amount', 12, 2)->nullable()->after('is_personal_lead');
        }
        if (!Schema::hasColumn('leads', 'vintage_year')) {
            $table->integer('vintage_year')->nullable()->after('turnover_amount');
        }
        if (!Schema::hasColumn('leads', 'bank_name')) {
            $table->string('bank_name')->nullable()->after('vintage_year');
        }
        $table->softDeletes();
         $table->string('state', 255)->nullable()->after('dob');
            $table->string('district', 255)->nullable()->after('state');
            $table->string('city', 255)->nullable()->after('district');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_type', 'voice_recording', 'is_personal_lead','state', 'district', 'city']);
        });
    }
};
