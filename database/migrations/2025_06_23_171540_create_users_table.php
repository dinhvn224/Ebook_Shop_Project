<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number', 20)->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('avatar_url')->nullable();
            $table->enum('role', ['USER', 'ADMIN'])->default('USER');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
