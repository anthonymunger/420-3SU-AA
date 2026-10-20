<?php

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
