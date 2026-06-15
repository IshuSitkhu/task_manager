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

    // Project
    $table->foreignId('project_id')
        ->constrained()
        ->cascadeOnDelete();

    // Epic
    $table->foreignId('epic_id')
        ->constrained()
        ->cascadeOnDelete();

    // Sprint (SAFE FIX)
    $table->foreignId('sprint_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('title');
    $table->text('description')->nullable();

    // User assigned
    $table->foreignId('assigned_to')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->enum('status', ['todo', 'in_progress', 'review', 'done'])
        ->default('todo');

    $table->enum('priority', ['low', 'medium', 'high', 'critical'])
        ->default('medium');

    $table->enum('type', ['feature', 'bug', 'ui', 'backend', 'test'])
        ->default('feature');

    $table->string('github_link')->nullable();
    $table->date('due_date')->nullable();

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
