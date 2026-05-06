<?php

declare(strict_types=1);

$source = __DIR__ . '/../public/images/products';
$destination = __DIR__ . '/../storage/app/public/images/products';
$overwrite = in_array('--force', $argv, true);

if (!is_dir($source)) {
    exit("Source folder does not exist: {$source}\n");
}

if (!is_dir($destination)) {
    mkdir($destination, 0777, true);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$copied = 0;
$skipped = 0;

foreach ($iterator as $item) {
    $sourcePath = $item->getPathname();
    $relativePath = substr($sourcePath, strlen($source) + 1);
    $relativePath = str_replace('\\', '/', $relativePath);

    $destinationPath = $destination . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if ($item->isDir()) {
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        continue;
    }

    $destinationDir = dirname($destinationPath);
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0777, true);
    }

    if (file_exists($destinationPath) && !$overwrite) {
        $skipped++;
        continue;
    }

    if (!copy($sourcePath, $destinationPath)) {
        echo "Failed to copy: {$relativePath}\n";
        continue;
    }

    $copied++;
}

echo "Done.\n";
echo "Source: {$source}\n";
echo "Destination: {$destination}\n";
echo "Copied: {$copied}\n";
echo "Skipped: {$skipped}\n";