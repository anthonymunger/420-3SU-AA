<?php
declare(strict_types=1);
require __DIR__ . "/../src/controllers/TachesController.php";
require __DIR__ . "/../src/controllers/ProjetsController.php";

$action = $_GET['action'] ?? 'afficher_taches';

// --- ACTION : afficher toutes les tâches -------------------------------------
if ($action === 'afficher_taches' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    afficherTaches();
}

// --- ACTION : formulaire ajout tâche (GET) -----------------------------------
elseif ($action === 'ajouter_tache' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    afficherAjoutTache();
}

// --- ACTION : traitement ajout tâche (POST) ----------------------------------
elseif ($action === 'ajouter_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ajouterTache();
}

// --- ACTION : formulaire modification tâche (POST → affiche form) ------------
elseif ($action === 'modifierform' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    afficherModificationTache();
}

// --- ACTION : traitement modification tâche (POST) ---------------------------
elseif ($action === 'modifier_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    modifierTache();
}

// --- ACTION : supprimer une tâche (POST) -------------------------------------
elseif ($action === 'supprimer_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    supprimerTache();
}

// --- ACTION : démarrer une tâche (POST) --------------------------------------
elseif ($action === 'demarrer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    demarrerTacheController();
}

// --- ACTION : terminer une tâche (POST) --------------------------------------
elseif ($action === 'terminer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    terminerTacheController();
}

// --- ACTION : afficher tous les projets --------------------------------------
elseif ($action === 'afficher_projets' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    afficherProjets();
}

// --- ACTION : formulaire ajout projet (GET) ----------------------------------
elseif ($action === 'ajouter_projet' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    afficherAjoutProjet();
}

// --- ACTION : traitement ajout projet (POST) ---------------------------------
elseif ($action === 'ajouter_projet' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ajouterProjet();
}

// --- ACTION : supprimer un projet (POST) -------------------------------------
elseif ($action === 'supprimer_projet' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    supprimerProjet();
} else {
    header('Location: ?action=afficher_taches');
    exit;
}
