<?php
require_once __DIR__ . '/../../autoload.php';

if (file_exists(__DIR__ . '/../../autoload.php')) {
    echo "Autoload found";
} else {
    echo "Autoload not found";
}

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

foreach ($configs as $file) {
    $source = __DIR__ . '/' . $file;
    $destination = getcwd() . '/' . $file;
    copy($source, $destination); // Supprimez la vérification de l'existence du fichier
}

foreach ($dirs as $dir) {
    $sourceDir = __DIR__ . '/' . $dir;
    $destDir = getcwd() . '/' . $dir;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $subPathName = $iterator->getSubPathName();
        $destinationPath = $destDir . '/' . $subPathName;

        if ($item->isDir()) {
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
        } else {
            copy($item, $destinationPath); // Ici aussi, supprimez la vérification de l'existence du fichier
        }
    }
    spl_autoload_register(__NAMESPACE__.'\Autoload::load', true, true);
}
