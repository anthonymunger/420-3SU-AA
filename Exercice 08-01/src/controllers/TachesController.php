<?php
require __DIR__ . "/../models/connexionDatabase.php";
require __DIR__ . "/../models/taches.php";

function afficherTaches():void {
    $taches = getTaches();

    require __DIR__ . "/../views/taches/afficher.php";
}

function afficherAjoutTache():void {
    $projets = getProjets();

    require __DIR__ . "/../views/taches/ajouter.php";
}

function afficherModificationTache():void {
    $id    = (int)$_POST['id'];
    $tache = getTacheById($id);
    $projets = getProjets();

    require __DIR__ . "/../views/taches/modifier.php";
}

function ajouterTache():void {
    $titre      = $_POST['titre'];
    $description = $_POST['description'];
    $priorite   = $_POST['priorite'];
    $projet_id  = (int)$_POST['projet_id'];

    insertTache($titre, $description, $priorite, $projet_id);
    header('Location: ?action=afficher_taches');
}

function modifierTache():void {
    $id          = (int)$_POST['id'];
    $titre       = $_POST['titre'];
    $description = $_POST['description'];
    $priorite    = $_POST['priorite'];
    $projet_id   = (int)$_POST['projet_id'];

    updateTache($id, $titre, $description, $priorite, $projet_id);
    header('Location: ?action=afficher_taches');
}

function supprimerTache():void {
    $id = (int)$_POST['id'];
    deleteTache($id);
    header('Location: ?action=afficher_taches');
}

function demarrerTacheController():void {
    $id = (int)$_POST['id'];
    demarrerTache($id);
    header('Location: ?action=afficher_taches');
}

function terminerTacheController():void {
    $id = (int)$_POST['id'];
    terminerTache($id);
    header('Location: ?action=afficher_taches');
}