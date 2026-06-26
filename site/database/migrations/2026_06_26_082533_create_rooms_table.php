<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category');
            $table->integer('price');
            $table->string('price_label');
            $table->string('size');
            $table->integer('guests');
            $table->integer('beds');
            $table->integer('baths')->nullable();
            $table->decimal('sqm', 8, 2)->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews')->default(0);
            $table->text('excerpt');
            $table->longText('description');
            $table->string('image');
            $table->json('gallery');
            $table->json('amenities');
            $table->json('includes');
            $table->json('policies');
            $table->json('best_for')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
