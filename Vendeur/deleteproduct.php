<?php
session_start();
require_once("../utilisateur/function.php");

// Vérifie que l’utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: ../Utilisateur/connexion.php");
    exit();
}

// Vérifie que l’utilisateur est un vendeur
if (!uservendeur()) {
    echo "<p class='text-center text-red-600 font-semibold mt-10'>🚫 Accès refusé : seuls les vendeurs peuvent supprimer un produit.</p>";
    exit();
}

// Vérifie la présence d’un ID en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-center text-red-600 font-semibold mt-10'>❌ ID du produit manquant.</p>";
    exit();
}

$id = intval($_GET['id']);

// Tente la suppression
if (deleteProduct($id)) {
    echo "<script>alert('✅ Produit supprimé avec succès !'); window.location.href='listproduct.php';</script>";
} else {
    echo "<script>alert('❌ Erreur lors de la suppression du produit.'); window.location.href='listproduct.php';</script>";
}
?>