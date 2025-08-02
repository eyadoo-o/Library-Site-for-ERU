<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->text('description');
            $table->enum('status', ['pending', 'accepted', 'completed', 'cancelled'])->default('pending');
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->foreignId('exchange_with_id')
                  ->nullable()
                  ->constrained('skills')
                  ->onDelete('set null');
            $table->index(['student_id', 'skill_id', 'status']);
            $table->index(['mentor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_exchanges');
    }
};
