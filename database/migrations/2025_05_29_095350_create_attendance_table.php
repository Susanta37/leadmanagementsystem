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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();

            $table->string('check_in_location')->nullable();
            $table->string('check_out_location')->nullable();
            $table->string('check_in_coordinates')->nullable(); // e.g. "lat,lng"
            $table->string('check_out_coordinates')->nullable();
            $table->text('notes')->nullable();
            $table->string('checkin_image')->nullable();
            $table->string('checkout_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
