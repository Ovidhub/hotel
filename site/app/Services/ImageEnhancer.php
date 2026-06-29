<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Enhances an uploaded photo with PHP-GD and stores it on the public disk:
 * fixes orientation, downsizes oversized images, applies gentle contrast +
 * sharpening, and saves an optimized WebP. Falls back to storing the original
 * file unchanged if GD can't process it, so uploads never fail.
 */
class ImageEnhancer
{
    /** Longest edge (px) the stored image is scaled down to. */
    public const MAX_EDGE = 1600;

    /** WebP quality (0-100). */
    public const QUALITY = 82;

    /**
     * Enhance and store an uploaded image; returns the stored relative path
     * (e.g. "rooms/abc.webp").
     */
    public function enhanceAndStore(UploadedFile $file, string $dir): string
    {
        $image = @imagecreatefromstring((string) file_get_contents($file->getRealPath()));

        // Unsupported/corrupt image, or GD lacks WebP output — keep the original.
        if ($image === false || ! function_exists('imagewebp')) {
            return $file->store($dir, 'public');
        }

        $image = $this->fixOrientation($image, $file);
        $image = $this->downsize($image);
        $this->enhance($image);

        $path = trim($dir, '/') . '/' . Str::random(40) . '.webp';

        ob_start();
        imagewebp($image, null, self::QUALITY);
        $contents = (string) ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    /** Rotate JPEGs per their EXIF orientation flag (common for phone photos). */
    protected function fixOrientation(\GdImage $image, UploadedFile $file): \GdImage
    {
        if (! function_exists('exif_read_data') || $file->getClientOriginalExtension() === '') {
            return $image;
        }

        try {
            $exif = @exif_read_data($file->getRealPath());
        } catch (\Throwable) {
            return $image;
        }

        $orientation = $exif['Orientation'] ?? null;
        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $rotated = imagerotate($image, $angle, 0);
            if ($rotated !== false) {
                imagedestroy($image);
                return $rotated;
            }
        }

        return $image;
    }

    /** Scale down (never up) so the longest edge is at most MAX_EDGE. */
    protected function downsize(\GdImage $image): \GdImage
    {
        $w = imagesx($image);
        $h = imagesy($image);
        $longest = max($w, $h);

        if ($longest <= self::MAX_EDGE) {
            return $image;
        }

        $scale = self::MAX_EDGE / $longest;
        $resized = imagescale($image, (int) round($w * $scale), (int) round($h * $scale), IMG_BICUBIC);

        if ($resized !== false) {
            imagedestroy($image);
            return $resized;
        }

        return $image;
    }

    /** Gentle contrast/brightness lift plus an unsharp-style sharpen. */
    protected function enhance(\GdImage $image): void
    {
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Subtle, non-destructive adjustments (GD contrast is inverted: negative = more).
        imagefilter($image, IMG_FILTER_CONTRAST, -8);
        imagefilter($image, IMG_FILTER_BRIGHTNESS, 4);

        // Mild sharpen (unsharp-mask-like 3x3 kernel, divisor keeps it gentle).
        $kernel = [
            [-1, -1, -1],
            [-1, 16, -1],
            [-1, -1, -1],
        ];
        @imageconvolution($image, $kernel, 8, 0);
    }
}
