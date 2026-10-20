<?php

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
