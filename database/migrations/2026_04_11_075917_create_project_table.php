<?php

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('client')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('reference', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default(ProjectStatus::Draft->value);
            $table->string('priority', 20)->default(ProjectPriority::Medium->value);
            $table->string('health', 20)->default(ProjectHealth::Good->value);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'status']);
            $table->index(['client_id', 'due_date']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
