<?php

namespace App\Console\Commands;

use App\Services\ImageEnhancer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-off importer for the real hotel room photos: reads the "Hotel rooms"
 * folders (next to the project root), enhances each photo and writes optimized
 * WebP files to public/img/rooms/<slug>/, consumed by the RoomSeeder.
 * The photo named "main" (if present) is placed first, then numbered shots.
 *
 *   php artisan rooms:import-photos
 */
class ImportRoomPhotos extends Command
{
    protected $signature = 'rooms:import-photos {--max=24 : Max photos per room}';

    protected $description = 'Enhance and import the local "Hotel rooms" photo folders into public/img/rooms';

    /** Source folder name => destination slug. */
    protected array $map = [
        'Delux classic 45k' => 'deluxe-classic',
        'Alcove'            => 'alcove-room',
        'suit 85k'          => 'diplomatic-suite',
        'Presidential suite' => 'presidential-suite',
    ];

    public function handle(ImageEnhancer $enhancer): int
    {
        $root = dirname(base_path()) . DIRECTORY_SEPARATOR . 'Hotel rooms';

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

            $images = collect(File::files($src))
                ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp']));

            $main = $images->first(fn ($f) => str_starts_with(
                strtolower(pathinfo($f->getFilename(), PATHINFO_FILENAME)), 'main'
            ));

            $numbered = $images
                ->reject(fn ($f) => $main && $f->getPathname() === $main->getPathname())
                ->sortBy(fn ($f) => (int) pathinfo($f->getFilename(), PATHINFO_FILENAME), SORT_NUMERIC)
                ->values();

            $files = collect($main ? [$main] : [])
                ->concat($numbered)
                ->take($max)
                ->values();

            if ($files->isEmpty()) {
                $this->warn("No images in: {$folder}");
                continue;
            }

            $dest = public_path('img/rooms/' . $slug);
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

            $this->info("✓ {$slug}: {$n} photos → public/img/rooms/{$slug}");
        }

        $this->newLine();
        $this->info('Done. Run `php artisan migrate:fresh --seed` to rebuild rooms from these photos.');

        return self::SUCCESS;
    }
}
