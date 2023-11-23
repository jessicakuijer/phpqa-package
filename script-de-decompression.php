<?php
// script-de-decompression.php
$vendorDir = getenv('COMPOSER_VENDOR_DIR') ?: realpath(__DIR__.'/vendor');
$rootDir = dirname($vendorDir);

$zip = new ZipArchive();
$zipPath = $rootDir . '/files_to_copy_to_root.zip';

if ($zip->open($zipPath) === TRUE) {
    $zip->extractTo($rootDir);
    $zip->close();
    echo "Fichiers extraits avec succès dans $rootDir.\n";
} else {
    echo "Impossible d'ouvrir l'archive $zipPath.\n";
}
