<?php

namespace App\Console\Commands;

use App\Services\ImageEnhancer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-off importer: reads the real HB Apartment photo folders (added next to
 * the project root), enhances each photo (resize, sharpen, colour-correct) and
 * writes optimized WebP files to public/img/apartments/<slug>/, which are then
 * committed and consumed by the ApartmentSeeder.
 *
 *   php artisan apartments:import-photos
 */
class ImportApartmentPhotos extends Command
{
    protected $signature = 'apartments:import-photos {--max=12 : Max photos per apartment}';

    protected $description = 'Enhance and import the local HB Apartment photo folders into public/img/apartments';

    /** Source folder name => destination slug. */
    protected array $map = [
        'Classic room 100k'            => 'classic-room',
        'Delux classic 130k'           => 'deluxe-classic-apartment',
        'Supreme 2 bed apartment 350k' => 'supreme-2-bedroom-apartment',
        'Supreme 4 bed apartment 650k' => 'supreme-4-bedroom-apartment',
    ];

    public function handle(ImageEnhancer $enhancer): int
    {
        $root = dirname(base_path()) . DIRECTORY_SEPARATOR . 'HB Apartment';

        if (! File::isDirectory($root)) {
            $this->error("Source folder not found: {$root}");

            return self::FAILURE;
        }

        $max = (int) $this->option('max');

        foreach ($this->map as $folder => $slug) {
            $src = $root . DIRECTORY_SEPARATOR . $folder;

            if (! File::isDirectory($src)) {
                $this->warn("Skipping (missing): {$folder}");
                continue;
            }

            // Collect + natural-sort the source images.
            $files = collect(File::files($src))
                ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
                ->sortBy(fn ($f) => $f->getFilename(), SORT_NATURAL | SORT_FLAG_CASE)
                ->take($max)
                ->values();

            if ($files->isEmpty()) {
                $this->warn("No images in: {$folder}");
                continue;
            }

            $dest = public_path('img/apartments/' . $slug);
            File::deleteDirectory($dest);
            File::ensureDirectoryExists($dest);

            $n = 0;
            foreach ($files as $file) {
                $bytes = $enhancer->enhancedWebpFromPath($file->getPathname());
                if ($bytes === null) {
                    $this->warn("  Could not process: {$file->getFilename()}");
                    continue;
                }
                $n++;
                File::put($dest . DIRECTORY_SEPARATOR . $n . '.webp', $bytes);
            }

            $this->info("✓ {$slug}: {$n} photos → public/img/apartments/{$slug}");
        }

        $this->newLine();
        $this->info('Done. Run `php artisan migrate:fresh --seed` to rebuild apartments from these photos.');

        return self::SUCCESS;
    }
}
