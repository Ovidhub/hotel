<?php

if (! function_exists('theme_view')) {
    /**
     * Resolve a public view name against the active theme, falling back to
     * the flat (default = Benizia) view when the theme does not provide it.
     */
    function theme_view(string $name): string
    {
        $theme = config('hotel.theme', 'default');

        if ($theme && $theme !== 'default') {
            $themed = "themes.{$theme}.{$name}";
            if (view()->exists($themed)) {
                return $themed;
            }
        }

        return $name;
    }
}
