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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('midtrans_order_id')->unique()->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ['dp', 'termin_2', 'termin_3', 'settlement']);
            $table->enum('payment_method', ['transfer', 'cash', 'midtrans'])->default('midtrans');
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->string('proof_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
