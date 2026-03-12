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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pl_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('package_id')->constrained('wedding_packages')->onDelete('cascade');
            $table->string('title');
            $table->date('event_date');
            $table->string('venue');
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['planning', 'ongoing', 'completed', 'canceled'])->default('planning');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
