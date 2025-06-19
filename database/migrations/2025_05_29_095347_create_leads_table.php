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
         Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_lead_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->string('location');
            $table->string('company_name')->nullable();
            $table->decimal('lead_amount', 10, 2);
            $table->decimal('salary', 10, 2)->nullable();
            $table->integer('success_percentage');
            $table->string('expected_month');
            $table->text('remarks')->nullable();
            $table->enum('status', ['personal_lead','authorized', 'approved', 'rejected', 'completed'])->default('personal_lead');
            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
