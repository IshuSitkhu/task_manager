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
        Schema::create('epics', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'critical'
            ])->default('medium');

            $table->enum('status', [
                'not_started',
                'in_progress',
                'testing',
                'completed'
            ])->default('not_started');

            $table->date('planned_start_date')->nullable();

            $table->date('planned_end_date')->nullable();

            $table->unsignedTinyInteger('progress')
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epics');
    }
};
