<?php

use App\Models\Setting;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

if (! function_exists('setting')) {
    /**
     * Read a site setting, falling back to the provided default.
     */
    function setting(string $key, $default = null)
    {
        try {
            return Setting::read($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('media')) {
    /**
     * Resolve a stored media path to a public URL.
     *
     * Paths beginning with "theme/" point at the bundled theme assets, remote
     * URLs are returned untouched and everything else lives on the public disk.
     */
    function media(?string $path, ?string $fallback = null): string
    {
        $path = filled($path) ? $path : $fallback;

        if (blank($path)) {
            return asset('theme/assets/images/resources/causes-style1_image-1.jpg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'theme/')) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('theme_asset')) {
    function theme_asset(string $path): string
    {
        return asset('theme/'.ltrim($path, '/'));
    }
}

if (! function_exists('safe_html')) {
    /**
     * Clean rich text written in the administration before printing it raw:
     * keeps the formatting (titles, lists, tables, links, images) but strips
     * scripts, event handlers and dangerous URLs.
     */
    function safe_html(?string $html): string
    {
        static $sanitizer = null;

        $sanitizer ??= new HtmlSanitizer(
            (new HtmlSanitizerConfig)
                ->allowSafeElements()
                ->allowAttribute('class', '*')
                ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
                ->allowRelativeLinks()
                ->allowMediaSchemes(['http', 'https'])
                ->allowRelativeMedias()
                ->withMaxInputLength(-1)
        );

        return $sanitizer->sanitize((string) $html);
    }
}

if (! function_exists('csv_row')) {
    /**
     * Neutralise the cells of an exported CSV row: a value typed by a visitor
     * and starting with = + - @ would otherwise run as a formula in Excel.
     */
    function csv_row(array $cells): array
    {
        return array_map(
            fn ($cell) => is_string($cell) && preg_match('/^[=+\-@\t\r]/', $cell) ? "'".$cell : $cell,
            $cells
        );
    }
}

if (! function_exists('money')) {
    function money($amount, bool $withCents = false): string
    {
        return number_format((float) $amount, $withCents ? 2 : 0, ',', ' ').' '.setting('currency_symbol', 'FCFA');
    }
}
