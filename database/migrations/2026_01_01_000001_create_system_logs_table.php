<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Who performed the action
            $table->string('action'); // e.g., Update Permission, Approve Import
            $table->string('target_type'); // What was affected (e.g., Role, Product)
            $table->json('old_data')->nullable(); // Snapshot of data before action
            $table->json('new_data')->nullable(); // Snapshot of data after action
            $table->timestamps(); // includes created_at for exact time recording
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
