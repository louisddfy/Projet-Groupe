<?php

require '../Connect/db.php';
session_start();

function isAdmin() {
    if (!isset($_SESSION['id_user'])) {
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }

    $userId = $_SESSION['id_user'];

    $stmtUser = $pdo->prepare("SELECT * FROM Utilisateur WHERE id_user = ?");
    $stmtUser->execute([$userId]);
    $userExists = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$userExists) {
        return false;
    }

    $stmtGestion = $pdo->prepare("SELECT * FROM Gestionnaire WHERE id_user = ?");
    $stmtGestion->execute([$userId]);
    if ($stmtGestion->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }
    $stmtVendeur = $pdo->prepare("SELECT * FROM Vendeur WHERE id_user = ?");
    $stmtVendeur->execute([$userId]);
    if ($stmtVendeur->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    $stmtClient = $pdo->prepare("SELECT * FROM Client WHERE id_user = ?");
    $stmtClient->execute([$userId]);
    if ($stmtClient->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    // Si on arrive ici, l'utilisateur est dans Utilisateur mais pas dans les autres tables → Admin
    return true;
}


?>