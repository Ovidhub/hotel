<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('room');
            $table->string('check_in');
            $table->string('check_out');
            $table->string('guests');
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('booking_inquiries'); }
};
