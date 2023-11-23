<?php
// script-de-decompression.php
$vendorDir = getenv('COMPOSER_VENDOR_DIR') ?: realpath(__DIR__.'/vendor');
$rootDir = dirname($vendorDir);

// Chemin vers l'archive dans le package installé
$zipPath = $vendorDir . '/phpqa/phpqa-package/files_to_copy_to_root.zip';

$zip = new ZipArchive();
if ($zip->open($zipPath) === TRUE) {
    $zip->extractTo($rootDir);
    $zip->close();
    echo "Fichiers extraits avec succès dans $rootDir.\n";
} else {
    echo "Impossible d'ouvrir l'archive $zipPath.\n";
}
