<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Throwable;

class GenerateFavicons extends Command
{
    protected $signature = 'seo:generate-favicons
        {logo? : Путь к логотипу относительно корня проекта или public/}
        {--force-monogram : Всегда генерировать монограмму W32, даже если логотип найден}';

    protected $description = 'Generate favicon.ico and device icons from a logo using PHP GD (monogram fallback)';

    public function handle(): int
    {
        if (!extension_loaded('gd')) {
            $this->error('PHP GD extension is required. Install php-gd or use ImageMagick.');
            return self::FAILURE;
        }

        $logo = $this->option('force-monogram') ? null : $this->resolveLogo($this->argument('logo'));

        if ($logo) {
            if (strtolower(pathinfo($logo, PATHINFO_EXTENSION)) === 'svg') {
                $this->error('SVG cannot be processed by GD directly. Convert it to PNG first, e.g. with ImageMagick.');
                return self::FAILURE;
            }

            $source = $this->createImage($logo);

            if (!$source) {
                $this->error("Cannot read image: {$logo}");
                return self::FAILURE;
            }

            $this->info("Using logo: {$logo}");
            $square = $this->makeSquare($source);
            imagedestroy($source);
        } else {
            $this->warn('Logo not found. Generating brand monogram (W32).');
            $square = $this->makeMonogram(512);
        }

        if (!is_dir(public_path('favicons'))) {
            mkdir(public_path('favicons'), 0755, true);
        }

        $icoFrames = [];

        foreach ([16, 32, 48, 64] as $size) {
            $icoFrames[$size] = $this->png($square, $size);
        }

        file_put_contents(public_path('favicon.ico'), $this->ico($icoFrames));

        $files = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'favicon-48x48.png' => 48,
            'apple-touch-icon.png' => 180,
            'icon-192x192.png' => 192,
            'icon-512x512.png' => 512,
            'maskable-192x192.png' => 192,
            'maskable-512x512.png' => 512,
        ];

        foreach ($files as $name => $size) {
            file_put_contents(
                public_path("favicons/{$name}"),
                $this->png($square, $size)
            );
        }

        imagedestroy($square);

        $this->info('Favicons generated in public/favicon.ico and public/favicons/.');

        return self::SUCCESS;
    }

    private function resolveLogo(?string $logo): ?string
    {
        if ($logo) {
            foreach ([base_path($logo), public_path($logo), $logo] as $path) {
                if (is_string($path) && is_file($path)) {
                    return $path;
                }
            }
        }

        $candidates = [
            public_path('images/logo.png'),
            public_path('img/logo.png'),
            public_path('assets/img/logo.png'),
            public_path('favicon-source.png'),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function createImage(string $path): ?\GdImage
    {
        try {
            $contents = @file_get_contents($path);
        } catch (Throwable) {
            return null;
        }

        if ($contents === false) {
            return null;
        }

        $image = @imagecreatefromstring($contents);

        return $image === false ? null : $image;
    }

    private function makeSquare(\GdImage $source): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $size = 512;

        $canvas = imagecreatetruecolor($size, $size);

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);

        $scale = min($size / $width, $size / $height) * 0.92;

        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);

        $x = (int) round(($size - $newWidth) / 2);
        $y = (int) round(($size - $newHeight) / 2);

        imagecopyresampled(
            $canvas,
            $source,
            $x,
            $y,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        return $canvas;
    }

    private function makeMonogram(int $size): \GdImage
    {
        $canvas = imagecreatetruecolor($size, $size);
        imageantialias($canvas, true);

        $background = imagecolorallocate($canvas, 0x1A, 0x1A, 0x1A);
        $foreground = imagecolorallocate($canvas, 0xF9, 0xF7, 0xF2);

        imagefilledrectangle($canvas, 0, 0, $size, $size, $background);

        $points = [
            [0.18, 0.30],
            [0.34, 0.72],
            [0.50, 0.44],
            [0.66, 0.72],
            [0.82, 0.30],
        ];

        $thickness = max(2, (int) round($size * 0.10));
        imagesetthickness($canvas, $thickness);

        for ($i = 0; $i < count($points) - 1; $i++) {
            imageline(
                $canvas,
                (int) round($points[$i][0] * $size),
                (int) round($points[$i][1] * $size),
                (int) round($points[$i + 1][0] * $size),
                (int) round($points[$i + 1][1] * $size),
                $foreground
            );
        }

        return $canvas;
    }

    private function png(\GdImage $square, int $size): string
    {
        $image = imagecreatetruecolor($size, $size);

        imagealphablending($image, false);
        imagesavealpha($image, true);

        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        imagecopyresampled(
            $image,
            $square,
            0,
            0,
            0,
            0,
            $size,
            $size,
            imagesx($square),
            imagesy($square)
        );

        ob_start();
        imagepng($image, null, 9);
        imagedestroy($image);

        return (string) ob_get_clean();
    }

    private function ico(array $frames): string
    {
        $count = count($frames);

        $header = pack('vvv', 0, 1, $count);
        $entries = '';
        $data = '';

        $offset = 6 + $count * 16;

        foreach ($frames as $size => $png) {
            $byte = $size >= 256 ? 0 : $size;

            $entries .= pack(
                'CCCCvvVV',
                $byte,
                $byte,
                0,
                0,
                1,
                32,
                strlen($png),
                $offset
            );

            $data .= $png;
            $offset += strlen($png);
        }

        return $header . $entries . $data;
    }
}
