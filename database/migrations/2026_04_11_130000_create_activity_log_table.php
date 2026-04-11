<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();

            // The owning customer is denormalised on every row so we can read a
            // customer's full feed (and the dashboard's "recent activity") with
            // a single, indexable WHERE — without joining through the morph
            // subject and its parent project / client.
            $table->foreignId('customer_id')
                ->constrained('customer')
                ->cascadeOnDelete();

            // Project is denormalised too so the project timeline endpoint can
            // hit a composite (customer_id, project_id, created_at) index. The
            // value stays NULL for activities not bound to a project (e.g.
            // future client.* events) and survives the parent project being
            // deleted via nullOnDelete.
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('project')
                ->nullOnDelete();

            // Polymorphic actor (Customer or User), nullable for system events.
            $table->nullableMorphs('actor');

            // Polymorphic subject of the event (Project, ProjectMilestone, Task…).
            $table->morphs('subject');

            // Stable, machine-readable event key, e.g. `task.status_changed`.
            $table->string('event', 60);

            // Free-form structured payload (from/to status, snapshot fields…).
            $table->json('properties')->nullable();

            // Activities are immutable: only created_at is meaningful.
            $table->timestamp('created_at')->nullable();

            $table->index(['customer_id', 'project_id', 'created_at'], 'activity_log_customer_project_created_idx');
            $table->index(['customer_id', 'created_at'], 'activity_log_customer_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
