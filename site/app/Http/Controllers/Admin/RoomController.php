<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Concerns\ParsesListInput;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    use ParsesListInput;
    use HandlesMediaUploads;

    public function index()
    {
        $rooms = Room::orderBy('sort')->orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(RoomRequest $request)
    {
        $data = $this->buildData($request->validated(), null);

        if ($path = $this->storeUpload($request, 'image_file', 'rooms')) {
            $data['image'] = $path;
        }
        $data['gallery'] = $this->resolveGallery($request, 'rooms', $data['gallery']);

        Room::create($data);

        return redirect()->route('admin.rooms.index')
                         ->with('status', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(RoomRequest $request, Room $room)
    {
        $data = $this->buildData($request->validated(), $room);

        if ($path = $this->storeUpload($request, 'image_file', 'rooms')) {
            $data['image'] = $path;
        } else {
            unset($data['image']); // keep the existing image when none uploaded
        }
        $data['gallery'] = $this->resolveGallery($request, 'rooms', $data['gallery']);

        $room->update($data);

        return redirect()->route('admin.rooms.index')
                         ->with('status', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
                         ->with('status', 'Room deleted successfully.');
    }

    private function buildData(array $validated, ?Room $room): array
    {
        $price = (int) $validated['price'];

        // Derive slug
        $name = $validated['name'];
        if (empty($validated['slug'])) {
            $base = Str::slug($name);
            $slug = $base;
            $i    = 1;
            while (Room::where('slug', $slug)->when($room, fn ($q) => $q->where('id', '!=', $room->id))->exists()) {
                $slug = $base . '-' . $i++;
            }
        } else {
            $slug = $validated['slug'];
        }

        return [
            'name'        => $name,
            'slug'        => $slug,
            'category'    => $validated['category'],
            'price'       => $price,
            'price_label' => 'NGN ' . number_format($price),
            'size'        => $validated['size'] ?? null,
            'guests'      => (int) $validated['guests'],
            'beds'        => (int) $validated['beds'],
            'baths'       => isset($validated['baths']) ? (int) $validated['baths'] : null,
            'sqm'         => $validated['sqm'] ?? null,
            'rating'      => $validated['rating'] ?? null,
            'reviews'     => isset($validated['reviews']) ? (int) $validated['reviews'] : 0,
            'excerpt'     => $validated['excerpt'],
            'description' => $validated['description'],
            'image'       => $validated['image'] ?? null,
            'gallery'     => $this->parseList($validated['gallery'] ?? ''),
            'amenities'   => $this->parseList($validated['amenities'] ?? ''),
            'includes'    => $this->parseList($validated['includes'] ?? ''),
            'policies'    => $this->parseList($validated['policies'] ?? ''),
            'best_for'    => $this->parseList($validated['best_for'] ?? ''),
            'is_active'   => (bool) ($validated['is_active'] ?? false),
            'units'       => (int) ($validated['units'] ?? 1),
            'sort'        => (int) ($validated['sort'] ?? 0),
        ];
    }

}
