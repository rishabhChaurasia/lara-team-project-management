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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // Email of the invited user
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Project being invited to
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade'); // Who sent the invitation
            $table->string('role'); // Role to assign ('owner', 'manager', 'member')
            $table->string('token')->unique(); // Unique token for accepting invitation
            $table->timestamp('expires_at'); // When the invitation expires
            $table->timestamp('accepted_at')->nullable(); // When the invitation was accepted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
