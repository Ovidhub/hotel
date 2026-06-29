<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Per-bookable export token for the public .ics feed URL.
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('ical_token', 64)->nullable()->unique()->after('units');
        });
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('ical_token', 64)->nullable()->unique()->after('units');
        });

        // Imported (Booking.com / OTA) calendar feed URLs to pull blocks from.
        Schema::create('ical_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->string('label')->nullable();   // e.g. "Booking.com"
            $table->text('url');
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_error')->nullable();
            $table->timestamps();

            $table->index(['bookable_type', 'bookable_id']);
        });

        // Link imported blocks back to their feed so a re-sync can replace them.
        Schema::table('availability_blocks', function (Blueprint $table) {
            $table->unsignedBigInteger('ical_feed_id')->nullable()->after('source')->index();
        });

        // Backfill tokens for existing rows.
        foreach (['rooms', 'apartments'] as $tableName) {
            DB::table($tableName)->whereNull('ical_token')->orderBy('id')->get(['id'])
                ->each(fn ($row) => DB::table($tableName)->where('id', $row->id)->update(['ical_token' => Str::random(40)]));
        }
    }

    public function down(): void
    {
        Schema::table('availability_blocks', fn (Blueprint $table) => $table->dropColumn('ical_feed_id'));
        Schema::dropIfExists('ical_feeds');
        Schema::table('rooms', fn (Blueprint $table) => $table->dropColumn('ical_token'));
        Schema::table('apartments', fn (Blueprint $table) => $table->dropColumn('ical_token'));
    }
};
