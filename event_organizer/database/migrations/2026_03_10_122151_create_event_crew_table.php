<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_crew', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('jobdesk')->nullable();
            $table->string('session')->default('reception');
            $table->decimal('fee', 15, 2)->default(0);
            $table->enum('status', ['Vacant', 'Requested', 'Verified', 'Rejected'])->default('Vacant');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_crew');
    }
};
