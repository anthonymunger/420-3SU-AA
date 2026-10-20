<?php
require __DIR__ . "/../partials/header.php";

echo '
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

require __DIR__ . "/../partials/footer.php";