<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\AvailabilityBlock;
use App\Models\IcalFeed;
use App\Models\Room;
use App\Services\IcalService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('sort')->orderBy('name')->get();
        $apartments = Apartment::orderBy('sort')->orderBy('name')->get();

        return view('admin.availability.index', compact('rooms', 'apartments'));
    }

    public function show(string $type, int $id)
    {
        $bookable = $this->resolve($type, $id);

        $bookings = $bookable->bookings()
            ->whereIn('status', \App\Services\AvailabilityService::OCCUPYING_STATUSES)
            ->whereDate('check_out', '>=', now())
            ->orderBy('check_in')
            ->get();

        $blocks = $bookable->availabilityBlocks()
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        $feeds = $bookable->icalFeeds()->latest()->get();

        return view('admin.availability.show', compact('bookable', 'type', 'bookings', 'blocks', 'feeds'));
    }

    public function storeFeed(Request $request, string $type, int $id, IcalService $ical)
    {
        $bookable = $this->resolve($type, $id);

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'url'   => ['required', 'url', 'max:2000'],
        ]);

        $feed = $bookable->icalFeeds()->create([
            'label' => $data['label'] ?? 'Booking.com',
            'url'   => $data['url'],
        ]);

        $result = $ical->syncFeed($feed);

        return back()->with('status', $result['ok']
            ? "Feed added — imported {$result['imported']} blocked period(s)."
            : 'Feed added, but the first sync failed: ' . $result['error']);
    }

    public function syncFeed(IcalFeed $feed, IcalService $ical)
    {
        $result = $ical->syncFeed($feed);

        return back()->with('status', $result['ok']
            ? "Synced — imported {$result['imported']} blocked period(s)."
            : 'Sync failed: ' . $result['error']);
    }

    public function destroyFeed(IcalFeed $feed)
    {
        // Remove the blocks this feed imported, then the feed itself.
        $feed->bookable->availabilityBlocks()->where('ical_feed_id', $feed->id)->delete();
        $feed->delete();

        return back()->with('status', 'Calendar feed removed.');
    }

    public function storeBlock(Request $request, string $type, int $id)
    {
        $bookable = $this->resolve($type, $id);

        $data = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['nullable', 'string', 'max:255'],
        ]);

        $bookable->availabilityBlocks()->create([
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'reason'     => $data['reason'] ?? null,
            'source'     => 'manual',
        ]);

        return back()->with('status', 'Dates blocked successfully.');
    }

    public function destroyBlock(AvailabilityBlock $block)
    {
        $block->delete();

        return back()->with('status', 'Block removed — dates are open again.');
    }

    protected function resolve(string $type, int $id): Model
    {
        return match ($type) {
            'room'      => Room::findOrFail($id),
            'apartment' => Apartment::findOrFail($id),
            default     => abort(404),
        };
    }
}
