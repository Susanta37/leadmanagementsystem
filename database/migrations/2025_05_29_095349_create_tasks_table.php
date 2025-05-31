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
         Schema::create('tasks', function (Blueprint $table) {
             $table->id();
            $table->foreignId('team_lead_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('title');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            $table->json('activity_timeline')->nullable();

            $table->dateTime('assigned_date')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->json('attachments')->nullable(); 

            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
