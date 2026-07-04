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
        // Interiors added 2026-07-04 (bar, restaurant, dining, food, chef).
        'hotell/bar 2.jpeg'     => 'hotel-bar',
        'hotell/bar1.jpeg'      => 'hotel-bar-2',
        'hotell/restuarant.jpeg'  => 'hotel-restaurant',
        'hotell/restuarant1.jpeg' => 'hotel-restaurant-2',
        'hotell/poolbar.jpeg'   => 'hotel-poolbar',
        'apart/main.jpeg'       => 'chef',
        'apart/food1.jpeg'      => 'dish-1',
        'apart/food2.jpeg'      => 'dish-2',
        'apart/food3.jpeg'      => 'dish-3',
        'apart/1.jpeg'          => 'apartment-dining',
        'apart/pool.jpeg'       => 'apartment-poolside',
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
