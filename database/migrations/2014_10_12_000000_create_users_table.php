<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->binary('avatar')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('token')->unique();
            $table->boolean('is_verified')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE users ADD avatar MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
