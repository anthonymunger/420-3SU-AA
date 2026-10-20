<?php
require __DIR__ . "/../models/connexionDatabase.php";
require __DIR__ . "/../models/projets.php";

function afficherProjets():void {
    $projets = getProjets();

    require __DIR__ . "/../views/projets/afficher.php";
}

function afficherAjoutProjet():void {
    require __DIR__ . "/../views/projets/ajouter.php";
}

function ajouterProjet():void {
    $nom         = $_POST['nom'];
    $description = $_POST['description'];
    insertProjet($nom, $description);
    header('Location: ?action=afficher_projets');
}

function supprimerProjet():void {
    $id = (int)$_POST['id'];
    deleteProjet($id);
    header('Location: ?action=afficher_projets');
}