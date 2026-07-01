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

        $orientation = $this->orientationOf($file->getRealPath());
        $image = $this->rotate($image, $orientation);
        $image = $this->downsize($image);
        $this->enhance($image);

        $path = trim($dir, '/') . '/' . Str::random(40) . '.webp';

        Storage::disk('public')->put($path, $this->toWebp($image));

        return $path;
    }

    /**
     * Enhance an image on disk and return optimized WebP bytes (for importing
     * local photo files). Returns null if the file can't be processed.
     */
    public function enhancedWebpFromPath(string $absolutePath): ?string
    {
        $image = @imagecreatefromstring((string) @file_get_contents($absolutePath));

        if ($image === false || ! function_exists('imagewebp')) {
            return null;
        }

        $image = $this->rotate($image, $this->orientationOf($absolutePath));
        $image = $this->downsize($image);
        $this->enhance($image);

        return $this->toWebp($image);
    }

    protected function toWebp(\GdImage $image): string
    {
        ob_start();
        imagewebp($image, null, self::QUALITY);
        $bytes = (string) ob_get_clean();
        imagedestroy($image);

        return $bytes;
    }

    /** Read a JPEG's EXIF orientation flag (common for phone photos). */
    protected function orientationOf(string $path): ?int
    {
        if (! function_exists('exif_read_data')) {
            return null;
        }

        try {
            $exif = @exif_read_data($path);
        } catch (\Throwable) {
            return null;
        }

        return $exif['Orientation'] ?? null;
    }

    /** Rotate the image upright per an EXIF orientation flag. */
    protected function rotate(\GdImage $image, ?int $orientation): \GdImage
    {
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
