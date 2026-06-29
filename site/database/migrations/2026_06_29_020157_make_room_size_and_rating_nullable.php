<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('size')->nullable()->change();
            $table->decimal('rating', 3, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('size')->nullable(false)->change();
            $table->decimal('rating', 3, 2)->default(0)->nullable(false)->change();
        });
    }
};
