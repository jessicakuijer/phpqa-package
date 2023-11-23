<?php

// Chemins des fichiers de configuration dans votre package
$configs = [
    'phpmd.xml',
    'phpcs.xml.dist',
    'grumphp.yml',
    'phpstan.dist.neon',
    'qa.sh'
];

$dirs = [
    'kaktus'
];

// Copie des fichiers de configuration
foreach ($configs as $file) {
    $source = __DIR__ . '/' . $file;
    $destination = getcwd() . '/' . $file;
    if (!file_exists($destination)) {
        copy($source, $destination);
    }
}

// Copie des dossiers
foreach ($dirs as $dir) {
    $sourceDir = __DIR__ . '/' . $dir;
    $destDir = getcwd() . '/' . $dir;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $fileInfo) {
        $targetPath = $destDir;

        if ($fileInfo->isDir()) {
            // Créer un sous-dossier si nécessaire
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        } else {
            // Copier un fichier
            copy($fileInfo->getRealPath(), $targetPath);
        }

        if ($fileInfo->isDir()) {
            // Créer un sous-dossier si nécessaire
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        } else {
            // Copier un fichier
            copy($fileInfo->getRealPath(), $targetPath);
        }
    }
}
