<?php
require __DIR__ . "/../partials/header.php";

echo '
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
