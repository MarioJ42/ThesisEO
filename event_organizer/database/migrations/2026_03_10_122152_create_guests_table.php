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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number', 50)->nullable();
            $table->integer('pax_invited')->default(1);
            $table->string('table_name', 50)->nullable();
            $table->string('barcode_token')->unique()->nullable();
            $table->enum('status', ['attending', 'not_attending', 'checked_in'])->default('attending');
            $table->dateTime('check_in_time')->nullable();
            $table->integer('pax_actual')->nullable();
            $table->integer('angpao_count')->nullable();
            $table->enum('angpao_type', ['fisik', 'digital'])->nullable();
            $table->boolean('angpao_titipan')->default(false);
            $table->integer('souvenir_given')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
