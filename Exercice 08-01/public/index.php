<?php
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

// --- CONNEXION BASE DE DONNÉES -----------------------------------------------
define('DB_HOST',    'localhost');
define('DB_NAME',    'gestion_taches');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8');

class connexionDatabase
{
    private static $pdo;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}



// --- FONCTIONS MODÈLE — PROJETS ----------------------------------------------

function getProjets() {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->query("SELECT * FROM projets ORDER BY nom");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProjetById($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertProjet($nom, $description) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("INSERT INTO projets (nom, description) VALUES (:nom, :description)");
    $stmt->execute([':nom' => $nom, ':description' => $description]);
}

// TODO
// -----------------------------------------------------------------------------
// DÉFI : Cette fonction plante si le projet contient des tâches.
// Pourquoi ?
//      - Votre réponse ici.
// Comment régleriez-vous ce problème dans le PHP et dans le SQL ?
//      - Votre réponse ici.
// -----------------------------------------------------------------------------
function deleteProjet($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM projets WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

// --- FONCTIONS MODÈLE — TÂCHES -----------------------------------------------

function getTaches() {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->query("
        SELECT taches.*, projets.nom AS nom_projet
        FROM taches
        JOIN projets ON taches.projet_id = projets.id
        ORDER BY FIELD(taches.priorite, 'haute', 'normale', 'basse'), taches.titre
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTacheById($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("
        SELECT taches.*, projets.nom AS nom_projet
        FROM taches
        JOIN projets ON taches.projet_id = projets.id
        WHERE taches.id = :id
    ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertTache($titre, $description, $priorite, $projet_id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("
        INSERT INTO taches (titre, description, priorite, projet_id)
        VALUES (:titre, :description, :priorite, :projet_id)
    ");
    $stmt->execute([
        ':titre'       => $titre,
        ':description' => $description,
        ':priorite'    => $priorite,
        ':projet_id'   => (int)$projet_id,
    ]);
}

function updateTache($id, $titre, $description, $priorite, $projet_id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("
        UPDATE taches SET titre = :titre, description = :description,
        priorite = :priorite, projet_id = :projet_id
        WHERE id = :id
    ");
    $stmt->execute([
        ':id'          => (int)$id,
        ':titre'       => $titre,
        ':description' => $description,
        ':priorite'    => $priorite,
        ':projet_id'   => (int)$projet_id,
    ]);
}

function deleteTache($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("DELETE FROM taches WHERE id = :id");
    $stmt->execute([':id' => (int)$id]);
}

function demarrerTache($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("UPDATE taches SET statut = 'en cours' WHERE id = :id AND statut = 'à faire'");
    $stmt->execute([':id' => (int)$id]);
}

function terminerTache($id) {
    $pdo = connexionDatabase::getConnexion();
    $stmt = $pdo->prepare("UPDATE taches SET statut = 'terminé' WHERE id = :id AND statut = 'en cours'");
    $stmt->execute([':id' => (int)$id]);
}

// --- ROUTEUR -----------------------------------------------------------------

$action = $_GET['action'] ?? 'afficher_taches';

// --- ACTION : afficher toutes les tâches -------------------------------------
if ($action === 'afficher_taches' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $taches = getTaches();

    echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de tâches</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; }
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .haute { color: #c0392b; font-weight: bold; }
        .normale { color: #2980b9; }
        .basse { color: #27ae60; }
        .statut-terminé { text-decoration: line-through; color: #999; }
    </style>
</head>
<body>
    <nav>
        <a href="?action=afficher_taches">Toutes les tâches</a>
        <a href="?action=ajouter_tache">+ Nouvelle tâche</a>
        <a href="?action=afficher_projets">Projets</a>
        <a href="?action=ajouter_projet">+ Nouveau projet</a>
    </nav>
    <h1>Liste des tâches</h1>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Priorité</th>
                <th>Projet</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($taches as $tache) {
        $classe = 'statut-' . str_replace(' ', '-', $tache['statut']);
        echo '<tr>
            <td class="' . $classe . '">' . htmlspecialchars($tache['titre']) . '</td>
            <td class="' . $tache['priorite'] . '">' . htmlspecialchars($tache['priorite']) . '</td>
            <td>' . htmlspecialchars($tache['nom_projet']) . '</td>
            <td>' . htmlspecialchars($tache['statut']) . '</td>
            <td>';

        if ($tache['statut'] === 'à faire') {
            echo '<form method="POST" action="?action=demarrer" style="display:inline">
                    <input type="hidden" name="id" value="' . $tache['id'] . '">
                    <button type="submit">▶ Démarrer</button>
                  </form> ';
        }

        if ($tache['statut'] === 'en cours') {
            echo '<form method="POST" action="?action=terminer" style="display:inline">
                    <input type="hidden" name="id" value="' . $tache['id'] . '">
                    <button type="submit">✔ Terminer</button>
                  </form> ';
        }

        if ($tache['statut'] !== 'terminé') {
            echo '<form method="POST" action="?action=modifierform" style="display:inline">
                    <input type="hidden" name="id" value="' . $tache['id'] . '">
                    <button type="submit">Modifier</button>
                  </form> ';
        }

        echo '<form method="POST" action="?action=supprimer_tache" style="display:inline"
                onsubmit="return confirm(\'Supprimer cette tâche ?\')">
                <input type="hidden" name="id" value="' . $tache['id'] . '">
                <button type="submit">Supprimer</button>
              </form>';

        echo '</td></tr>';
    }

    echo '  </tbody>
        </table>
    <footer style="margin-top:40px; color:#999;">© ' . date('Y') . ' Gestion de tâches</footer>
</body>
</html>';
}

// --- ACTION : formulaire ajout tâche (GET) -----------------------------------
elseif ($action === 'ajouter_tache' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $projets = getProjets();

    echo '<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Ajouter une tâche</title>
<style>body{font-family:Arial,sans-serif;margin:20px;} form{width:50%;border:1px solid #ccc;padding:20px;} label{display:block;margin-top:10px;} input,select,textarea{width:100%;padding:6px;box-sizing:border-box;} button{margin-top:15px;padding:8px 20px;}</style>
</head>
<body>
    <nav><a href="?action=afficher_taches">← Retour aux tâches</a></nav>
    <h1>Nouvelle tâche</h1>
    <form method="POST" action="?action=ajouter_tache">
        <label>Titre *
            <input type="text" name="titre" required>
        </label>
        <label>Description
            <textarea name="description" rows="3"></textarea>
        </label>
        <label>Priorité *
            <select name="priorite" required>
                <option value="basse">Basse</option>
                <option value="normale" selected>Normale</option>
                <option value="haute">Haute</option>
            </select>
        </label>
        <label>Projet *
            <select name="projet_id" required>';

    foreach ($projets as $projet) {
        echo '<option value="' . $projet['id'] . '">' . htmlspecialchars($projet['nom']) . '</option>';
    }

    echo '      </select>
        </label>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>';
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

    echo '<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Modifier une tâche</title>
<style>body{font-family:Arial,sans-serif;margin:20px;} form{width:50%;border:1px solid #ccc;padding:20px;} label{display:block;margin-top:10px;} input,select,textarea{width:100%;padding:6px;box-sizing:border-box;} button{margin-top:15px;padding:8px 20px;}</style>
</head>
<body>
    <nav><a href="?action=afficher_taches">← Retour aux tâches</a></nav>
    <h1>Modifier la tâche</h1>
    <form method="POST" action="?action=modifier_tache">
        <input type="hidden" name="id" value="' . $tache['id'] . '">
        <label>Titre *
            <input type="text" name="titre" value="' . htmlspecialchars($tache['titre']) . '" required>
        </label>
        <label>Description
            <textarea name="description" rows="3">' . htmlspecialchars($tache['description']) . '</textarea>
        </label>
        <label>Priorité *
            <select name="priorite" required>';

    foreach (['basse', 'normale', 'haute'] as $p) {
        $selected = $tache['priorite'] === $p ? 'selected' : '';
        echo '<option value="' . $p . '" ' . $selected . '>' . ucfirst($p) . '</option>';
    }

    echo '      </select>
        </label>
        <label>Projet *
            <select name="projet_id" required>';

    foreach ($projets as $projet) {
        $selected = $tache['projet_id'] === $projet['id'] ? 'selected' : '';
        echo '<option value="' . $projet['id'] . '" ' . $selected . '>' . htmlspecialchars($projet['nom']) . '</option>';
    }

    echo '      </select>
        </label>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>';
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

    echo '<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Projets</title>
<style>body{font-family:Arial,sans-serif;margin:20px;} nav a{margin-right:15px;} table{width:60%;border-collapse:collapse;margin-top:20px;} th,td{border:1px solid #ccc;padding:8px;} th{background:#f0f0f0;}</style>
</head>
<body>
    <nav>
        <a href="?action=afficher_taches">Tâches</a>
        <a href="?action=ajouter_projet">+ Nouveau projet</a>
    </nav>
    <h1>Projets</h1>
    <table>
        <thead>
            <tr><th>Nom</th><th>Description</th><th>Actions</th></tr>
        </thead>
        <tbody>';

    foreach ($projets as $projet) {
        echo '<tr>
            <td>' . htmlspecialchars($projet['nom']) . '</td>
            <td>' . htmlspecialchars($projet['description']) . '</td>
            <td>
                <form method="POST" action="?action=supprimer_projet" style="display:inline"
                    onsubmit="return confirm(\'Supprimer ce projet et toutes ses tâches ?\')">
                    <input type="hidden" name="id" value="' . $projet['id'] . '">
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>';
    }

    echo '  </tbody>
        </table>
    <footer style="margin-top:40px;color:#999;">© ' . date('Y') . ' Gestion de tâches</footer>
</body>
</html>';
}

// --- ACTION : formulaire ajout projet (GET) ----------------------------------
elseif ($action === 'ajouter_projet' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    echo '<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Nouveau projet</title>
<style>body{font-family:Arial,sans-serif;margin:20px;} form{width:50%;border:1px solid #ccc;padding:20px;} label{display:block;margin-top:10px;} input,textarea{width:100%;padding:6px;box-sizing:border-box;} button{margin-top:15px;padding:8px 20px;}</style>
</head>
<body>
    <nav><a href="?action=afficher_projets">← Retour aux projets</a></nav>
    <h1>Nouveau projet</h1>
    <form method="POST" action="?action=ajouter_projet">
        <label>Nom *
            <input type="text" name="nom" required>
        </label>
        <label>Description
            <textarea name="description" rows="3"></textarea>
        </label>
        <button type="submit">Créer le projet</button>
    </form>
</body>
</html>';
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
