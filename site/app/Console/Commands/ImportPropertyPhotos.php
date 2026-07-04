<?php

namespace App\Console\Commands;

use App\Services\ImageEnhancer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-off importer for the general property photos (hotel & apartment
 * buildings/exteriors) from the "full building" folder next to the project
 * root. Enhances each and writes optimized WebP to public/img/property/,
 * used for the hero, about, gallery, blog and schema imagery.
 *
 *   php artisan property:import-photos
 */
class ImportPropertyPhotos extends Command
{
    protected $signature = 'property:import-photos';

    protected $description = 'Enhance and import the local "full building" photos into public/img/property';

    /** Source filename => destination webp basename. */
    protected array $map = [
        'hotel.jpeg'          => 'hotel-exterior',
        'hotel 1.jpeg'        => 'hotel-entrance',
        'hotel compond.jpeg'  => 'hotel-compound',
        'hotel location.jpeg' => 'hotel-aerial',
        'apartment.jpeg'      => 'apartment-exterior',
        'apart 1.jpeg'        => 'apartment-terrace',
        'apart 2.jpeg'        => 'apartment-courtyard',
        'apart location.jpeg' => 'apartment-aerial',
        'hotel pool.jpeg'     => 'hotel-pool',
        'apart pool.jpeg'     => 'apartment-pool',
        'apart pool 1.jpeg'   => 'apartment-pool-2',
    ];

    public function handle(ImageEnhancer $enhancer): int
    {
        $root = dirname(base_path()) . DIRECTORY_SEPARATOR . 'full building';

        if (! File::isDirectory($root)) {
            $this->error("Source folder not found: {$root}");

            return self::FAILURE;
        }

        $dest = public_path('img/property');
        File::ensureDirectoryExists($dest);

        $n = 0;
        foreach ($this->map as $file => $name) {
            $src = $root . DIRECTORY_SEPARATOR . $file;

            if (! File::isFile($src)) {
                $this->warn("Skipping (missing): {$file}");
                continue;
            }

            $bytes = $enhancer->enhancedWebpFromPath($src);
            if ($bytes === null) {
                $this->warn("  Could not process: {$file}");
                continue;
            }

            File::put($dest . DIRECTORY_SEPARATOR . $name . '.webp', $bytes);
            $this->info("✓ {$name}.webp");
            $n++;
        }

        $this->newLine();
        $this->info("Done. {$n} photos → public/img/property");

        return self::SUCCESS;
    }
}
