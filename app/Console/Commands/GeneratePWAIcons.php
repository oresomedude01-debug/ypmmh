<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePWAIcons extends Command
{
    protected $signature = 'pwa:generate-icons';
    protected $description = 'Generate all PWA icon sizes from the source icon.png';

    /**
     * Standard PWA icon sizes
     */
    private array $sizes = [72, 96, 128, 144, 152, 192, 384, 512];
    private array $maskableSizes = [192, 512];

    public function handle(): int
    {
        $iconsDir = public_path('icons');
        $sourcePath = $iconsDir . '/icon.png';

        if (!file_exists($sourcePath)) {
            $this->error("Source icon not found at: {$sourcePath}");
            $this->info('Please place your master icon as public/icons/icon.png');
            return 1;
        }

        if (!extension_loaded('gd')) {
            $this->error('GD extension is not available. Please enable it in php.ini.');
            return 1;
        }

        // Load the source PNG
        $sourceInfo = getimagesize($sourcePath);
        if (!$sourceInfo || $sourceInfo[2] !== IMAGETYPE_PNG) {
            $this->error('Source file is not a valid PNG image.');
            return 1;
        }

        $sourceImage = @imagecreatefrompng($sourcePath);
        if (!$sourceImage) {
            $this->error('Failed to load source PNG image.');
            return 1;
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        $this->info("Source icon: {$sourceWidth}x{$sourceHeight}px");
        $this->newLine();

        // Generate standard icons
        $this->info('Generating standard icons...');
        foreach ($this->sizes as $size) {
            $filename = "icon-{$size}x{$size}.png";
            $this->resizeAndSave($sourceImage, $sourceWidth, $sourceHeight, $size, $iconsDir, $filename);
        }

        $this->newLine();

        // Generate maskable icons (with safe zone padding)
        $this->info('Generating maskable icons (with safe zone)...');
        foreach ($this->maskableSizes as $size) {
            $filename = "icon-maskable-{$size}x{$size}.png";
            $this->resizeAndSaveMaskable($sourceImage, $sourceWidth, $sourceHeight, $size, $iconsDir, $filename);
        }

        imagedestroy($sourceImage);

        $this->newLine();
        $this->info('✅ All PWA icons generated successfully from icon.png!');
        $this->newLine();

        // Summary table
        $rows = [];
        foreach ($this->sizes as $size) {
            $file = "icon-{$size}x{$size}.png";
            $path = "{$iconsDir}/{$file}";
            $fileSize = file_exists($path) ? $this->formatBytes(filesize($path)) : 'N/A';
            $rows[] = [$file, "{$size}x{$size}", 'any', $fileSize];
        }
        foreach ($this->maskableSizes as $size) {
            $file = "icon-maskable-{$size}x{$size}.png";
            $path = "{$iconsDir}/{$file}";
            $fileSize = file_exists($path) ? $this->formatBytes(filesize($path)) : 'N/A';
            $rows[] = [$file, "{$size}x{$size}", 'maskable', $fileSize];
        }
        $this->table(['File', 'Size', 'Purpose', 'File Size'], $rows);

        return 0;
    }

    /**
     * Resize source image and save as PNG (standard icon)
     */
    private function resizeAndSave($source, int $srcW, int $srcH, int $targetSize, string $dir, string $filename): void
    {
        $dest = imagecreatetruecolor($targetSize, $targetSize);

        // Preserve transparency
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
        imagefill($dest, 0, 0, $transparent);

        // High-quality resize
        imagecopyresampled($dest, $source, 0, 0, 0, 0, $targetSize, $targetSize, $srcW, $srcH);

        $outputPath = "{$dir}/{$filename}";
        imagepng($dest, $outputPath, 9); // Max compression
        imagedestroy($dest);

        $this->info("  ✓ {$filename}");
    }

    /**
     * Resize source image with safe-zone padding for maskable icons.
     * Maskable icons need the content within the inner 80% "safe zone"
     * so Android adaptive icons don't clip important parts.
     */
    private function resizeAndSaveMaskable($source, int $srcW, int $srcH, int $targetSize, string $dir, string $filename): void
    {
        $dest = imagecreatetruecolor($targetSize, $targetSize);

        // Fill background with theme color (#0B4D73)
        $bgColor = imagecolorallocate($dest, 11, 77, 115);
        imagefill($dest, 0, 0, $bgColor);

        // The icon content should fit within the inner 80% (safe zone)
        $safeSize = (int) ($targetSize * 0.80);
        $offset = (int) (($targetSize - $safeSize) / 2);

        // High-quality resize into the safe zone area
        imagecopyresampled($dest, $source, $offset, $offset, 0, 0, $safeSize, $safeSize, $srcW, $srcH);

        $outputPath = "{$dir}/{$filename}";
        imagepng($dest, $outputPath, 9);
        imagedestroy($dest);

        $this->info("  ✓ {$filename} (maskable, 80% safe zone)");
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576)
            return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)
            return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
