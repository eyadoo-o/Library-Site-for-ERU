<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['book_request', 'system_feature', 'event_suggestion']);
            $table->enum('status', ['pending', 'under_review', 'approved', 'completed', 'rejected']);
            $table->integer('votes_count')->default(0);
            $table->timestamps();
        });

        Schema::create('feature_request_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_request_id')->constrained('feature_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_request_votes');
        Schema::dropIfExists('feature_requests');
    }
};
