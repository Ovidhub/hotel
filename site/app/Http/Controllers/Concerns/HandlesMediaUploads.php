<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

/**
 * Stores admin-uploaded images on the public disk and returns their
 * relative paths (e.g. "rooms/abc.jpg"), served via the storage symlink.
 */
trait HandlesMediaUploads
{
    /**
     * Store a single uploaded file from $field into $dir on the public disk.
     * Returns the stored relative path, or null when no file was uploaded.
     */
    protected function storeUpload(Request $request, string $field, string $dir): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($dir, 'public');
    }

    /**
     * Store multiple uploaded gallery files into $dir on the public disk.
     *
     * @return array<int, string> stored relative paths
     */
    protected function storeGalleryUploads(Request $request, string $dir, string $field = 'gallery_files'): array
    {
        $paths = [];

        if ($request->hasFile($field)) {
            foreach ($request->file($field) as $file) {
                $paths[] = $file->store($dir, 'public');
            }
        }

        return $paths;
    }
}
