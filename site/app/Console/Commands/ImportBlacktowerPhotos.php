<?php

namespace App\Console\Commands;

use App\Services\ImageEnhancer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-off importer that downloads the Black Tower theme's imagery from the
 * live WordPress site (blacktowerhotelsasaba.com) and stores local copies
 * under public/img/themes/blacktower/, so the self-hosted theme no longer
 * depends on the wp-content host at runtime.
 *
 * Opaque photographs are re-encoded to optimized WebP via ImageEnhancer.
 * Transparent assets (logo, decorative shapes) are saved as-is in PNG to
 * preserve alpha, since the enhancer flattens images when re-encoding.
 *
 *   php artisan blacktower:import-photos
 */
class ImportBlacktowerPhotos extends Command
{
    protected $signature = 'blacktower:import-photos';

    protected $description = 'Download and localize the Black Tower theme images into public/img/themes/blacktower';

    protected string $base = 'https://blacktowerhotelsasaba.com';

    /** Opaque photos: source path (relative to $base) => destination webp basename. */
    protected array $webpMap = [
        '/wp-content/uploads/2025/11/tour-1-550x640.png' => 'tour-1',
        '/wp-content/uploads/2025/11/tour-2-550x640.png' => 'tour-2',
        '/wp-content/uploads/2025/11/tour-3-550x640.png' => 'tour-3',
        '/wp-content/uploads/2025/11/tour-4-550x640.png' => 'tour-4',
        '/wp-content/uploads/2025/11/tour-5-550x640.png' => 'tour-5',
        '/wp-content/uploads/2025/11/tour-6-550x640.png' => 'tour-6',
        '/wp-content/uploads/2025/11/tour-7-550x640.png' => 'tour-7',
        '/wp-content/uploads/2025/11/tour-8-550x640.png' => 'tour-8',
        '/wp-content/uploads/2025/11/tour-9-550x640.png' => 'tour-9',
        '/wp-content/uploads/2025/11/tour-10-550x640.png' => 'tour-10',
        '/wp-content/uploads/2025/11/tour-11-550x640.png' => 'tour-11',
        '/wp-content/uploads/2025/11/WhatsApp-Image-2025-11-16-at-4.06.51-PM.jpeg' => 'hero-bg',
        '/wp-content/uploads/2023/02/bg-01.jpg' => 'why-bg',
        '/wp-content/uploads/2025/11/bg-021.png' => 'service-bg',
        '/wp-content/uploads/2023/02/bg-05.jpg' => 'amenities-bg',
    ];

    /** Transparent assets: source path (relative to $base) => destination png basename(s). */
    protected array $pngMap = [
        '/wp-content/uploads/2022/12/blacklogowht.png' => ['logo', 'favicon'],
        '/wp-content/themes/boliin/assets/images/about-shape.png' => ['about-shape'],
        '/wp-content/uploads/2022/12/bg-shape-01.png' => ['bg-shape-01'],
        '/wp-content/uploads/2022/12/bg-shape-02.png' => ['bg-shape-02'],
        '/wp-content/uploads/2023/10/bg-iconbox.png' => ['bg-iconbox'],
    ];

    public function handle(ImageEnhancer $enhancer): int
    {
        $dest = public_path('img/themes/blacktower');
        File::ensureDirectoryExists($dest);

        $ok = 0;
        $failed = 0;

        foreach ($this->webpMap as $path => $name) {
            $url = $this->base . $path;
            $bytes = $this->download($url);

            if ($bytes === null) {
                $this->warn("Skipping (download failed): {$url}");
                $failed++;
                continue;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'bt_');
            File::put($tmp, $bytes);

            $webp = $enhancer->enhancedWebpFromPath($tmp);
            @unlink($tmp);

            if ($webp === null) {
                $this->warn("  Could not process: {$url}");
                $failed++;
                continue;
            }

            File::put($dest . DIRECTORY_SEPARATOR . $name . '.webp', $webp);
            $this->info("✓ {$name}.webp");
            $ok++;
        }

        foreach ($this->pngMap as $path => $names) {
            $url = $this->base . $path;
            $bytes = $this->download($url);

            if ($bytes === null) {
                $this->warn("Skipping (download failed): {$url}");
                $failed++;
                continue;
            }

            foreach ($names as $name) {
                File::put($dest . DIRECTORY_SEPARATOR . $name . '.png', $bytes);
                $this->info("✓ {$name}.png");
            }
            $ok++;
        }

        $this->newLine();
        $this->info("Done. {$ok} source images imported, {$failed} failed → public/img/themes/blacktower");

        return $failed > 0 && $ok === 0 ? self::FAILURE : self::SUCCESS;
    }

    /** Download a URL's bytes, returning null on any failure. */
    protected function download(string $url): ?string
    {
        try {
            $context = stream_context_create([
                'http' => ['timeout' => 30, 'ignore_errors' => true],
                'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
            ]);
            $bytes = @file_get_contents($url, false, $context);
        } catch (\Throwable) {
            $bytes = false;
        }

        if ($bytes === false || $bytes === '') {
            return null;
        }

        return $bytes;
    }
}
