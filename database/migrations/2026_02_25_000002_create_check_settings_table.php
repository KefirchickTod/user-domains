<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('interval_minutes')->default(5);
            $table->unsignedInteger('timeout_seconds')->default(10);
            $table->enum('method', ['GET', 'HEAD'])->default('HEAD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_settings');
    }
};
