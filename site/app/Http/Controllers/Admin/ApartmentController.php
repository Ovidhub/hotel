<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApartmentRequest;
use App\Models\Apartment;
use Illuminate\Support\Str;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::orderBy('sort')->orderBy('name')->get();
        return view('admin.apartments.index', compact('apartments'));
    }

    public function create()
    {
        return view('admin.apartments.create');
    }

    public function store(ApartmentRequest $request)
    {
        $data = $this->buildData($request->validated(), null);
        Apartment::create($data);

        return redirect()->route('admin.apartments.index')
                         ->with('status', 'Apartment created successfully.');
    }

    public function edit(Apartment $apartment)
    {
        return view('admin.apartments.edit', compact('apartment'));
    }

    public function update(ApartmentRequest $request, Apartment $apartment)
    {
        $data = $this->buildData($request->validated(), $apartment);
        $apartment->update($data);

        return redirect()->route('admin.apartments.index')
                         ->with('status', 'Apartment updated successfully.');
    }

    public function destroy(Apartment $apartment)
    {
        $apartment->delete();

        return redirect()->route('admin.apartments.index')
                         ->with('status', 'Apartment deleted successfully.');
    }

    private function buildData(array $validated, ?Apartment $apartment): array
    {
        $price = (int) $validated['price'];

        // Derive slug
        $name = $validated['name'];
        if (empty($validated['slug'])) {
            $base = Str::slug($name);
            $slug = $base;
            $i    = 1;
            while (Apartment::where('slug', $slug)->when($apartment, fn ($q) => $q->where('id', '!=', $apartment->id))->exists()) {
                $slug = $base . '-' . $i++;
            }
        } else {
            $slug = $validated['slug'];
        }

        return [
            'name'        => $name,
            'slug'        => $slug,
            'type'        => $validated['type'],
            'price'       => $price,
            'price_label' => 'NGN ' . number_format($price),
            'status'      => $validated['status'],
            'image'       => $validated['image'],
            'gallery'     => $this->parseList($validated['gallery'] ?? ''),
            'bedrooms'    => (int) $validated['bedrooms'],
            'bathrooms'   => (int) $validated['bathrooms'],
            'occupancy'   => (int) $validated['occupancy'],
            'description' => $validated['description'],
            'amenities'   => $this->parseList($validated['amenities'] ?? ''),
            'is_active'   => (bool) ($validated['is_active'] ?? false),
            'sort'        => (int) ($validated['sort'] ?? 0),
        ];
    }

    private function parseList(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        $lines = preg_split('/[\r\n]+/', $value);
        $items = [];
        foreach ($lines as $line) {
            foreach (explode(',', $line) as $part) {
                $trimmed = trim($part);
                if ($trimmed !== '') {
                    $items[] = $trimmed;
                }
            }
        }

        return $items;
    }
}
