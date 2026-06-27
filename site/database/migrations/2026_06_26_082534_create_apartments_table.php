<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('type');
            $table->integer('price');
            $table->string('price_label');
            $table->string('status')->default('Available');
            $table->string('image');
            $table->json('gallery')->nullable();
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('occupancy');
            $table->text('description');
            $table->json('amenities');
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
