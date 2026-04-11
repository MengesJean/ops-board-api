<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('project')
                ->cascadeOnDelete();
            $table->foreignId('project_milestone_id')
                ->nullable()
                ->constrained('project_milestone')
                ->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 20)->default(TaskStatus::Todo->value);
            $table->string('priority', 20)->default(TaskPriority::Medium->value);
            $table->unsignedInteger('position');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'position']);
            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'priority']);
            $table->index(['project_id', 'due_date']);
            $table->index('project_milestone_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
