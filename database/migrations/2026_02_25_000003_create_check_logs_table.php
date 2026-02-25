<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->boolean('is_successful');
            $table->text('error_message')->nullable();
            $table->timestamp('checked_at');

            $table->index('domain_id');
            $table->index('checked_at');
            $table->index('is_successful');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_logs');
    }
};
