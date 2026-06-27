<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Resolves stored media values that may be either an external URL
 * (existing seeded data) or a path on the public disk (admin uploads).
 */
trait HasMediaUrls
{
    /**
     * Resolve the main image to a usable URL.
     */
    public function imageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }

    /**
     * The full set of images for the detail scroll gallery:
     * the main image first, then any gallery images, de-duplicated.
     *
     * @return array<int, string>
     */
    public function galleryUrls(): array
    {
        $paths = array_merge(
            [$this->image],
            is_array($this->gallery) ? $this->gallery : []
        );

        return collect($paths)
            ->filter()
            ->map(fn ($path) => $this->resolveMediaUrl($path))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Turn a stored value into a browser-usable URL.
     * External URLs and root-relative paths pass through unchanged;
     * stored upload paths are served from the public storage symlink.
     */
    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
