#!/usr/bin/env bash
#
# Passe tous les blocs ACF du dossier courant en Blocks V3 + Auto Inline Editing.
# A executer DANS le dossier acf-blocks/ du theme.
#
#   ./acf-blocks-v3.sh --dry-run   # simulation, n'ecrit rien
#   ./acf-blocks-v3.sh             # applique (sauvegarde automatique)
#
set -euo pipefail

DRY_RUN=0
[ "${1:-}" = "--dry-run" ] && DRY_RUN=1

command -v php >/dev/null 2>&1 || { echo "ERREUR: php introuvable dans le PATH."; exit 1; }

shopt -s nullglob
FILES=( */block.json )
if [ ${#FILES[@]} -eq 0 ]; then
  echo "ERREUR: aucun */block.json ici. Place-toi dans le dossier acf-blocks/ du theme."
  echo "        (dossier courant: $PWD)"
  exit 1
fi
echo "Dossier   : $PWD"
echo "Blocs     : ${#FILES[@]}"

if [ "$DRY_RUN" -eq 0 ]; then
  BACKUP="../acf-blocks-block-json-backup-$(date +%Y%m%d-%H%M%S).tar.gz"
  tar -czf "$BACKUP" "${FILES[@]}"
  echo "Sauvegarde: $BACKUP"
  echo "            (restauration: tar -xzf $BACKUP -C .)"
fi
echo

DRY_RUN="$DRY_RUN" php -d error_reporting=E_ALL -d display_errors=1 -- "$@" <<'PHP'
<?php
$dry   = getenv('DRY_RUN') === '1';
$files = glob('*/block.json');
sort($files);

$done = $skip = $fail = 0;

foreach ($files as $file) {
    $src = file_get_contents($file);
    if ($src === false) {
        printf("  ECHEC   %-45s lecture impossible\n", $file);
        $fail++;
        continue;
    }

    // Deja traite ?
    if (strpos($src, '"blockVersion"') !== false) {
        printf("  DEJA OK %-45s\n", $file);
        $skip++;
        continue;
    }

    $before = json_decode($src, true);
    if (!is_array($before)) {
        printf("  ECHEC   %-45s JSON invalide avant modification\n", $file);
        $fail++;
        continue;
    }
    if (!isset($before['acf'])) {
        printf("  IGNORE  %-45s pas de cle \"acf\" (bloc non ACF)\n", $file);
        $skip++;
        continue;
    }

    // Respecte les fins de ligne du fichier (CRLF vs LF)
    $eol = (strpos($src, "\r\n") !== false) ? "\r\n" : "\n";

    // Insertion apres renderTemplate / renderCallback, en reprenant son indentation
    $pattern = '/^([ \t]*)"(renderTemplate|renderCallback)"(\s*):(\s*)"([^"]*)"/m';
    if (!preg_match($pattern, $src, $m)) {
        printf("  ECHEC   %-45s ni renderTemplate ni renderCallback trouve\n", $file);
        $fail++;
        continue;
    }
    $indent = $m[1];
    $anchor = $m[0];

    $insert = $anchor . ',' . $eol
            . $indent . '"blockVersion": 3,' . $eol
            . $indent . '"autoInlineEditing": true';

    $out = preg_replace($pattern, addcslashes($insert, '\\$'), $src, 1, $count);
    if ($out === null || $count !== 1) {
        printf("  ECHEC   %-45s substitution impossible\n", $file);
        $fail++;
        continue;
    }

    // Garde-fou : le JSON doit rester valide ET identique au precedent
    // a l'ajout des deux cles pres.
    $after = json_decode($out, true);
    if (!is_array($after)) {
        printf("  ECHEC   %-45s JSON invalide apres modification, non ecrit\n", $file);
        $fail++;
        continue;
    }
    $check = $after;
    unset($check['acf']['blockVersion'], $check['acf']['autoInlineEditing']);
    if ($check !== $before) {
        printf("  ECHEC   %-45s modification inattendue, non ecrit\n", $file);
        $fail++;
        continue;
    }
    if ($after['acf']['blockVersion'] !== 3 || $after['acf']['autoInlineEditing'] !== true) {
        printf("  ECHEC   %-45s cles non appliquees, non ecrit\n", $file);
        $fail++;
        continue;
    }

    if ($dry) {
        printf("  A FAIRE %-45s\n", $file);
        $done++;
        continue;
    }

    if (file_put_contents($file, $out) === false) {
        printf("  ECHEC   %-45s ecriture impossible (droits ?)\n", $file);
        $fail++;
        continue;
    }
    printf("  OK      %-45s\n", $file);
    $done++;
}

printf("\n%s : %d modifie(s), %d ignore(s), %d echec(s)\n",
    $dry ? 'SIMULATION' : 'TERMINE', $done, $skip, $fail);

if ($fail > 0) {
    echo "\nDes blocs n'ont pas ete traites. Rien n'a ete ecrit pour ceux-la ;\n";
    echo "les autres sont OK. Corrige-les a la main ou restaure la sauvegarde.\n";
    exit(1);
}
if (!$dry && $done > 0) {
    echo "\nPense a deployer aussi, si ce n'est pas deja fait :\n";
    echo "  - acf-blocks/swiper/assets/js/view.js\n";
    echo "  - inc-functions/register-scripts.php\n";
}
PHP
