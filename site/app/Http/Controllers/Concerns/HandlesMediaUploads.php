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
}
