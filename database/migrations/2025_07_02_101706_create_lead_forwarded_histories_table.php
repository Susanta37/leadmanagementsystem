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
        Schema::create('lead_forwarded_histories', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('lead_id');            // Refers to leads.id
            $table->unsignedBigInteger('sender_user_id');     // Refers to users.id (who forwarded)
            $table->unsignedBigInteger('receiver_user_id');   // Refers to users.id (who received)

            // Status and timestamp
            $table->boolean('is_forwarded')->default(0);      // 0 = No, 1 = Yes
            $table->timestamp('forwarded_at')->useCurrent();  // Time of forwarding

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_forwarded_histories');
    }
};
