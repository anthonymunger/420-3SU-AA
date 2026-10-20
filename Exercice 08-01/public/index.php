<?php
declare(strict_types=1);
// =============================================================================
// FICHIER DE DÉPART — Gestion de tâches avec projets
// =============================================================================
// CONSIGNE : Ce fichier contient TOUT le code de l'application dans un seul
// fichier. Votre travail est de le réorganiser selon l'architecture MVC :
//
//   config/
//     database.php          ← connexion PDO
//   public/
//     css/style.css
//     index.php             ← routeur + point d'entrée
//   src/
//     models/
//       connexionDatabase.php
//       projets.php         ← fonctions CRUD pour les projets
//       taches.php          ← fonctions CRUD pour les tâches
//     controllers/
//       ProjetsController.php
//       TachesController.php
//     views/
//       layouts/app.php
//       partials/header.php
//       partials/footer.php
//       projets/afficher.php
//       projets/ajouter.php
//       taches/afficher.php
//       taches/ajouter.php
//       taches/modifier.php
//
// =============================================================================

require __DIR__ . "/../src/models/connexionDatabase.php";
require __DIR__ . "/../src/models/taches.php";
require __DIR__ . "/../src/models/projets.php";

// --- ROUTEUR -----------------------------------------------------------------

$action = $_GET['action'] ?? 'afficher_taches';

// --- ACTION : afficher toutes les tâches -------------------------------------
if ($action === 'afficher_taches' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $taches = getTaches();

    require __DIR__ . "/../src/views/taches/afficher.php";
}

// --- ACTION : formulaire ajout tâche (GET) -----------------------------------
elseif ($action === 'ajouter_tache' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $projets = getProjets();

    require __DIR__ . "/../src/views/taches/ajouter.php";
}

// --- ACTION : traitement ajout tâche (POST) ----------------------------------
elseif ($action === 'ajouter_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre      = $_POST['titre'];
    $description = $_POST['description'];
    $priorite   = $_POST['priorite'];
    $projet_id  = (int)$_POST['projet_id'];

    insertTache($titre, $description, $priorite, $projet_id);
    header('Location: ?action=afficher_taches');
    exit;
}

// --- ACTION : formulaire modification tâche (POST → affiche form) ------------
elseif ($action === 'modifierform' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)$_POST['id'];
    $tache = getTacheById($id);
    $projets = getProjets();

    require __DIR__ . "/../src/views/taches/modifier.php";
}

// --- ACTION : traitement modification tâche (POST) ---------------------------
elseif ($action === 'modifier_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)$_POST['id'];
    $titre       = $_POST['titre'];
    $description = $_POST['description'];
    $priorite    = $_POST['priorite'];
    $projet_id   = (int)$_POST['projet_id'];

    updateTache($id, $titre, $description, $priorite, $projet_id);
    header('Location: ?action=afficher_taches');
    exit;
}

// --- ACTION : supprimer une tâche (POST) -------------------------------------
elseif ($action === 'supprimer_tache' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    deleteTache($id);
    header('Location: ?action=afficher_taches');
    exit;
}

// --- ACTION : démarrer une tâche (POST) --------------------------------------
elseif ($action === 'demarrer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    demarrerTache($id);
    header('Location: ?action=afficher_taches');
    exit;
}

// --- ACTION : terminer une tâche (POST) --------------------------------------
elseif ($action === 'terminer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    terminerTache($id);
    header('Location: ?action=afficher_taches');
    exit;
}

// --- ACTION : afficher tous les projets --------------------------------------
elseif ($action === 'afficher_projets' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $projets = getProjets();

    require __DIR__ . "/../src/views/projets/afficher.php";
}

// --- ACTION : formulaire ajout projet (GET) ----------------------------------
elseif ($action === 'ajouter_projet' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require __DIR__ . "/../src/views/projets/ajouter.php";
}

// --- ACTION : traitement ajout projet (POST) ---------------------------------
elseif ($action === 'ajouter_projet' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom         = $_POST['nom'];
    $description = $_POST['description'];
    insertProjet($nom, $description);
    header('Location: ?action=afficher_projets');
    exit;
}

// --- ACTION : supprimer un projet (POST) -------------------------------------
elseif ($action === 'supprimer_projet' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    deleteProjet($id);
    header('Location: ?action=afficher_projets');
    exit;
} else {
    header('Location: ?action=afficher_taches');
    exit;
}
