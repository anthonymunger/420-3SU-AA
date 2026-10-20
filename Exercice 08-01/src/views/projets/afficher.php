<?php
require __DIR__ . "/../partials/header.php";

echo '
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

require __DIR__ . "/../partials/footer.php";