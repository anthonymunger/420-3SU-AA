<?php
declare(strict_types=1);

require __DIR__ . "/../src/models/connexionDatabase.php";
require __DIR__ . "/../src/controllers/TachesController.php";
require __DIR__ . "/../src/controllers/ProjetsController.php";

$action = $_GET['action'] ?? 'afficher_taches';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    match(true) {
        $action === 'afficher_taches'   => afficherTaches(),
        $action === 'ajouter_tache'     => afficherAjoutTache(),
        $action === 'modifier_tache'    => afficherModificationTache(),
        $action === 'afficher_projets'  => afficherProjets(),
        $action === 'ajouter_projets'   => afficherAjoutProjet(),
        default => header('Location: ?action=afficher_taches'),
    };
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    match(true) {
        $action === 'ajouter_tache'=> ajouterTache(),
        $action === 'modifier_tache'=> modifierTache(),
        $action === 'supprimer_tache'=> supprimerTache(),
        $action === 'demarrer'=> demarrerTacheController(),
        $action === 'terminer'=> terminerTacheController(),
        $action === 'ajouter_projet'=> ajouterProjet(),
        $action === 'supprimer_projet'=> supprimerProjet(),
        default => header('Location: index.php'),
    };
}