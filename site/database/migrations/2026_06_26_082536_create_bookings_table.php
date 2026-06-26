<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');
            $table->integer('guests');
            $table->integer('total');
            $table->integer('commitment_percent');
            $table->integer('commitment_fee');
            $table->integer('balance_due');
            $table->string('status')->default('Pending Payment');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('proof_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['bookable_type', 'bookable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
