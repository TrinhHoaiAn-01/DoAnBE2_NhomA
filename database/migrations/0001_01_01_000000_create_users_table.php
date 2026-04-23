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
        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 191);

            $table->string('email', 191)->unique();

            // PHONE
            $table->string('phone', 20)->nullable()->after('email');

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password', 191);

            // ROLE (1: admin, 2: user, ...)
            $table->unsignedBigInteger('role_id')->default(2);

            // STATUS (active / inactive)
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->string('remember_token', 100)->nullable();

            $table->timestamps();
        });

        // PASSWORD RESET TOKENS
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token', 191);
            $table->timestamp('created_at')->nullable();
        });

        // SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->index('user_id');

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->longText('payload');

            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};