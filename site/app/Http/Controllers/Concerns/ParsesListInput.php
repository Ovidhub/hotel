<?php

namespace App\Http\Controllers\Concerns;

trait ParsesListInput
{
    /**
     * Convert a newline- or comma-separated textarea string into a clean array.
     */
    protected function parseList(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        // Split on newlines first, then commas within each line
        $lines = preg_split('/[\r\n]+/', $value);
        $items = [];
        foreach ($lines as $line) {
            foreach (explode(',', $line) as $part) {
                $trimmed = trim($part);
                if ($trimmed !== '') {
                    $items[] = $trimmed;
                }
            }
        }

        return $items;
    }
}
