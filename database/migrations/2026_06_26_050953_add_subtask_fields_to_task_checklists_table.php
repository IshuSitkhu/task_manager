<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_checklists', function (Blueprint $table) {

            $table->text('description')->nullable();

            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->date('due_date')->nullable();

            $table->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('task_checklists', function (Blueprint $table) {

            $table->dropForeign(['assigned_to']);

            $table->dropColumn([
                'description',
                'assigned_to',
                'due_date',
                'image'
            ]);
        });
    }
};
