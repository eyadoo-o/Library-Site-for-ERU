<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_user_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->timestamp('viewed_at');
            $table->text('user_agent')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->timestamps();

            //unique constraint to prevent duplicate views within time window
            $table->unique(['document_id', 'ip_address', 'user_id'], 'unique_document_view');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_user_views');
    }
};
