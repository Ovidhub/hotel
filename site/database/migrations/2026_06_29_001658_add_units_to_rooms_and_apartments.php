<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedInteger('units')->default(1)->after('is_active');
        });

        Schema::table('apartments', function (Blueprint $table) {
            $table->unsignedInteger('units')->default(1)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', fn (Blueprint $table) => $table->dropColumn('units'));
        Schema::table('apartments', fn (Blueprint $table) => $table->dropColumn('units'));
    }
};
