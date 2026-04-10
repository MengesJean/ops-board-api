<?php

use App\Enums\ClientStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained('customer')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('status', 20)->default(ClientStatus::Lead->value);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['customer_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
