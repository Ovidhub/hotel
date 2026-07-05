<?php

namespace App\Http\Controllers\Concerns;

use App\Services\ImageEnhancer;
use Illuminate\Http\Request;

/**
 * Stores admin-uploaded images on the public disk and returns their
 * relative paths (e.g. "rooms/abc.webp"). Each image is run through the
 * ImageEnhancer (resize, sharpen, colour-correct, optimized WebP) on the way in.
 */
trait HandlesMediaUploads
{
    /**
     * Store a single uploaded, enhanced image from $field into $dir.
     * Returns the stored relative path, or null when no file was uploaded.
     */
    protected function storeUpload(Request $request, string $field, string $dir): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return app(ImageEnhancer::class)->enhanceAndStore($request->file($field), $dir);
    }

    /**
     * Store multiple uploaded, enhanced gallery images into $dir.
     *
     * @return array<int, string> stored relative paths
     */
    protected function storeGalleryUploads(Request $request, string $dir, string $field = 'gallery_files'): array
    {
        $paths = [];

        if ($request->hasFile($field)) {
            $enhancer = app(ImageEnhancer::class);
            foreach ($request->file($field) as $file) {
                $paths[] = $enhancer->enhanceAndStore($file, $dir);
            }
        }

        return $paths;
    }

    /**
     * Resolve the final ordered gallery for a room/apartment.
     *
     * Newly uploaded files (gallery_files[]) are stored first. When the
     * admin gallery manager submits a `gallery_order` list, it is honoured:
     * existing paths are kept in order and `__new__:N` placeholders are
     * swapped for the N-th freshly stored upload. When `gallery_order` is
     * absent (e.g. API/tests), we fall back to the legacy behaviour of
     * appending new uploads to the given $fallback list.
     *
     * @param  array<int, string>  $fallback  existing gallery from the plain `gallery` field
     * @return array<int, string>
     */
    protected function resolveGallery(Request $request, string $dir, array $fallback): array
    {
        $stored = $this->storeGalleryUploads($request, $dir);

        $order = $request->input('gallery_order');

        if (is_string($order) && $order !== '') {
            $tokens = json_decode($order, true);

            if (is_array($tokens)) {
                $result = [];
                foreach ($tokens as $token) {
                    if (! is_string($token) || $token === '') {
                        continue;
                    }
                    if (str_starts_with($token, '__new__:')) {
                        $index = (int) substr($token, strlen('__new__:'));
                        if (isset($stored[$index])) {
                            $result[] = $stored[$index];
                        }
                    } else {
                        $result[] = $token;
                    }
                }

                return array_values(array_unique($result));
            }
        }

        // Legacy: keep existing list, append new uploads at the end.
        return array_values(array_merge($fallback, $stored));
    }
}
