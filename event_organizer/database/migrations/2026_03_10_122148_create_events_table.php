<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pl_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('package_id')->nullable()->constrained('wedding_packages')->onDelete('set null');
            $table->string('title');
            $table->date('event_date');
            $table->string('venue')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->enum('status', ['draft', 'planning', 'ongoing', 'completed', 'canceled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
