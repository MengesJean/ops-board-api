<?php

use App\Enums\MilestoneStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_milestone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('project')
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 20)->default(MilestoneStatus::Pending->value);
            $table->unsignedInteger('position');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'position']);
            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_milestone');
    }
};
