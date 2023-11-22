#!/bin/sh

# Initialiser un code de sortie
exitCode=0

# Exécuter PHPMD
echo "Lancement de PHP Mess Detector..."
composer run phpmd

# Vérifier si PHPMD a détecté des erreurs
if [ $? -ne 0 ]; then
    echo "Erreurs détectées par PHPMD"
    exitCode=1
fi
echo "Analyse PHPMD terminée."

# Exécution de CodeSniffer
echo "Exécution de CodeSniffer..."
output=$(./vendor/bin/phpcs ./src/)
echo "$output"

# Vérifier si CodeSniffer a détecté des erreurs
if [ -n "$output" ]; then
    echo "Erreurs détectées par CodeSniffer"
    exitCode=1
fi
echo "Analyse CodeSniffer terminée."

# Exécution de PHPStan
echo "Exécution de PHPStan..."
output=$(./vendor/bin/phpstan analyse)
echo "$output"
echo "Analyse PHPStan terminée."

# Execution de Kaktus
echo "Exécution de Kaktus..."
output2=$(php ./kaktus/kaktus -d ./src/ -l [critical / warning / notice])
if echo "$output2" | grep -q "deprecated"; then
    echo "Erreurs dépréciées trouvées par Kaktus mais non prises en compte"
    exitCode=0
fi
echo "$output2"

# Vérification des différents niveaux de logs pour Kaktus
if echo "$output2" | grep -q "[ CRITICAL] "; then
    echo "Erreur CRITICAL trouvée par Kaktus"
    exitCode=0
elif echo "$output2" | grep -q "[ WARNING ]"; then
    echo "Alerte WARNING trouvée par Kaktus"
    exitCode=0
elif echo "$output2" | grep -q "[ NOTICE ]"; then
    echo "Alerte NOTICE trouvée par Kaktus"
    exitCode=0
fi
echo "Analyse Kaktus terminée."

# Afficher le message de résultat
if [ $exitCode -eq 0 ]; then
    echo "
    ███████╗██╗   ██╗ ██████╗ ██████╗███████╗███████╗██████╗ ███████╗██████╗
    ██╔════╝██║   ██║██╔════╝██╔════╝██╔════╝██╔════╝██╔══██╗██╔════╝██╔══██╗
    ███████╗██║   ██║██║     ██║     █████╗  █████╗  ██║  ██║█████╗  ██║  ██║
    ╚════██║██║   ██║██║     ██║     ██╔══╝  ██╔══╝  ██║  ██║██╔══╝  ██║  ██║
    ███████║╚██████╔╝╚██████╗╚██████╗███████╗███████╗██████╔╝███████╗██████╔╝
    
    your code is almost perfect
    
    "
else
    echo "
    ███████╗ █████╗ ██╗██╗     ███████╗██████╗
    ██╔════╝██╔══██╗██║██║     ██╔════╝██╔══██╗
    █████╗  ███████║██║██║     █████╗  ██║  ██║
    ██╔══╝  ██╔══██║██║██║     ██╔══╝  ██║  ██║
    ██║     ██║  ██║██║███████╗███████╗██████╔╝
    ╚═╝     ╚═╝  ╚═╝╚═╝╚══════╝╚══════╝╚═════╝ 
    
    you need to fix your code
    
    "
fi

# Sortie avec le code de sortie approprié
exit $exitCode
