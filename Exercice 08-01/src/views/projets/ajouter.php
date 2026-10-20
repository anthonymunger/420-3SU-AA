<?php
require __DIR__ . "/../partials/header.php";

echo '
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