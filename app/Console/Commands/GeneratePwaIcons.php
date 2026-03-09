<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:generate-icons';

    protected $description = 'Génère les icônes PNG 192x192 et 512x512 pour la PWA (nécessaires pour Chrome)';

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('L\'extension PHP GD n\'est pas installée.');

            return self::FAILURE;
        }

        $dir = public_path('images');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ([192, 512] as $size) {
            $path = "{$dir}/logo-{$size}.png";
            if ($this->createIcon($size, $path)) {
                $this->info("✓ Icône {$size}x{$size} créée : {$path}");
            } else {
                $this->error("✗ Échec création icône {$size}x{$size}");

                return self::FAILURE;
            }
        }

        // Copie pour le fallback du package (apple-touch-icon, bouton install)
        $fallback = public_path('logo.png');
        if (copy("{$dir}/logo-512.png", $fallback)) {
            $this->info('✓ logo.png créé (fallback PWA)');
        }

        $this->newLine();
        $this->info('Exécutez : php artisan erag:update-manifest');

        return self::SUCCESS;
    }

    private function createIcon(int $size, string $path): bool
    {
        $img = @imagecreatetruecolor($size, $size);
        if (! $img) {
            return false;
        }

        imagealphablending($img, true);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        $yellow = $this->hexToRgb($img, '#FFD700');
        $navy = $this->hexToRgb($img, '#003366');

        $padding = (int) ($size * 0.08);
        $cx = (int) ($size / 2);
        $cy = (int) ($size / 2);
        $radius = (int) ($size / 2) - $padding;

        imagefilledellipse($img, $cx, $cy, $radius * 2, $radius * 2, $yellow);
        imagerectangle($img, 0, 0, $size - 1, $size - 1, $navy);

        $stroke = max(2, (int) ($size / 40));
        $lineLen = (int) ($size * 0.35);

        // Croix verticale
        for ($o = -$stroke; $o <= $stroke; $o++) {
            imageline($img, $cx + $o, $cy - $lineLen, $cx + $o, $cy + $lineLen, $navy);
        }
        // Croix horizontale
        for ($o = -$stroke; $o <= $stroke; $o++) {
            imageline($img, $cx - $lineLen, $cy + $o, $cx + $lineLen, $cy + $o, $navy);
        }

        // Lettre "P" (approximation avec rectangle)
        $fontSize = (int) ($size * 0.35);
        $fontFile = $this->getSystemFont();
        if ($fontFile) {
            $text = 'P';
            $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
            $tw = $bbox[2] - $bbox[0];
            $th = $bbox[1] - $bbox[7];
            $tx = $cx - (int) ($tw / 2);
            $ty = $cy + (int) ($th / 2) - (int) ($size * 0.02);
            imagettftext($img, $fontSize, 0, $tx, $ty, $navy, $fontFile, $text);
        } else {
            $rectSize = (int) ($size * 0.2);
            imagefilledrectangle($img, $cx - $rectSize, $cy - $rectSize, $cx + $rectSize, $cy + $rectSize, $navy);
        }

        $ok = imagepng($img, $path);
        imagedestroy($img);

        return $ok;
    }

    private function hexToRgb($img, string $hex): int
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return imagecolorallocate($img, $r, $g, $b);
    }

    private function getSystemFont(): ?string
    {
        $fonts = [
            'C:\\Windows\\Fonts\\arial.ttf',
            'C:\\Windows\\Fonts\\Arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
        ];

        foreach ($fonts as $f) {
            if (file_exists($f)) {
                return $f;
            }
        }

        return null;
    }
}
