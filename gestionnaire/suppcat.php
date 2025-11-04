<?php
session_start();
require_once("../utilisateur/function.php");

if (!isset($_GET['id'])) {
    header("Location: listcat.php");
    exit;
}

$id = (int) $_GET['id'];
$result = deleteCategory($id);

if ($result === true) {
    header("Location: listcat.php?message=deleted");
    exit;
} elseif ($result === "notfound") {
    echo "<p class='text-center text-red-600 mt-10'>❌ Catégorie introuvable.</p>";
    echo "<p class='text-center mt-4'><a href='listcat.php' class='text-gray-600 hover:underline'>⬅ Retour à la liste</a></p>";
    exit;
} else {
    echo "<p class='text-center text-red-600 mt-10'>❌ Erreur lors de la suppression.</p>";
    echo "<p class='text-center mt-4'><a href='listcat.php' class='text-gray-600 hover:underline'>⬅ Retour à la liste</a></p>";
    exit;
}
?>