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
        Schema::create('availability_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->date('start_date'); // first blocked night (inclusive)
            $table->date('end_date');   // last blocked night (inclusive)
            $table->string('reason')->nullable();
            $table->string('source')->default('manual'); // manual | bookingcom | ical
            $table->timestamps();

            $table->index(['bookable_type', 'bookable_id']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_blocks');
    }
};
