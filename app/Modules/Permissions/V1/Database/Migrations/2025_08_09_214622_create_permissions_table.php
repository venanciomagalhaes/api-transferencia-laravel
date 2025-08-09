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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_type_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreignId('user_type_id')->references('id')->on('users_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_type_permissions');
        Schema::dropIfExists('permissions');
    }
};
