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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->float('rating')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->timestamps();

            $table->unique(['name', 'mentor_id']); // A mentor shouldn't offer the same skill twice
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
