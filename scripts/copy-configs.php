<?php
$sourceDir = __DIR__ . '/files-to-copy-to-root/'; // Chemin vers vos fichiers de config dans le package
$targetDir = getcwd() . '/'; // Chemin racine du projet utilisateur

// Fonction pour copier récursivement un dossier
function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recursiveCopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

foreach (['.php.cs-fixer.dist.php', 'grumphp.yml', 'phpcs.xml.dist', 'phpstan.dist.neon', 'qa.sh'] as $file) {
    $sourceFile = $sourceDir . $file;
    $targetFile = $targetDir . $file;

    // Si le fichier existe, l'écraser
    if (file_exists($targetFile)) {
        unlink($targetFile);
    }

    // Copie du dossier kaktus
    $sourceKaktusDir = $sourceDir . 'kaktus/';
    $targetKaktusDir = $targetDir . 'kaktus/';
    if (!file_exists($targetKaktusDir)) {
        recursiveCopy($sourceKaktusDir, $targetKaktusDir);
    }

}
