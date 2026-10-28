<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Déclinaisons WebP d'une image téléversée, à plusieurs largeurs, pour srcset.
 *
 * L'original est conservé tel quel ; les déclinaisons sont rangées à côté
 * ({nom}-{largeur}.webp) et ne sont jamais plus larges que l'original.
 */
class ResponsiveImage
{
    public const WIDTHS = [1280, 1920, 2560, 3840];

    public const QUALITY = 82;

    /**
     * Génère les déclinaisons d'un fichier du disque public.
     *
     * @return array<int, string> largeur => chemin, triées par largeur croissante
     */
    public static function generate(string $path, array $widths = self::WIDTHS): array
    {
        $disk = Storage::disk('public');
        $source = self::load($disk->path($path));

        $originalWidth = imagesx($source);
        $originalHeight = imagesy($source);

        // Toujours au moins une déclinaison, à la taille d'origine si l'image est petite.
        $targets = array_filter($widths, fn (int $width) => $width < $originalWidth);
        $targets[] = min($originalWidth, max($widths));
        $targets = array_values(array_unique($targets));
        sort($targets);

        $base = preg_replace('/\.[^.\/]+$/', '', $path);
        $variants = [];

        foreach ($targets as $width) {
            $height = (int) round($originalHeight * $width / $originalWidth);
            $resized = imagescale($source, $width, $height, IMG_BICUBIC);

            ob_start();
            imagewebp($resized, null, self::QUALITY);
            $disk->put("{$base}-{$width}.webp", ob_get_clean());
            imagedestroy($resized);

            $variants[$width] = "{$base}-{$width}.webp";
        }

        imagedestroy($source);

        return $variants;
    }

    /**
     * @param  array<int, string>|null  $variants
     */
    public static function delete(?array $variants): void
    {
        if ($variants) {
            Storage::disk('public')->delete(array_values($variants));
        }
    }

    /**
     * @param  array<int, string>|null  $variants
     */
    public static function srcset(?array $variants): string
    {
        return collect($variants ?? [])
            ->map(fn (string $path, int $width) => media($path)." {$width}w")
            ->implode(', ');
    }

    /** Ouvre l'image en truecolor, redressée selon son orientation EXIF. */
    private static function load(string $file): \GdImage
    {
        $image = @imagecreatefromstring((string) @file_get_contents($file));

        if (! $image) {
            throw new RuntimeException("Image illisible : {$file}");
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $orientation = function_exists('exif_read_data')
            ? (@exif_read_data($file)['Orientation'] ?? 1)
            : 1;

        $rotated = match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => null,
        };

        if ($rotated) {
            imagedestroy($image);
            $image = $rotated;
        }

        return $image;
    }
}
