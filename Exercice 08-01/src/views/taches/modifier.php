<?php
require __DIR__ . "/../partials/header.php";

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
