<?php
session_start();
require_once("function.php");

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connectUser'])) {
    header("Location: connexion.php");
    exit;
}

// 2. Vérifier la présence de l'ID du produit
if (!isset($_GET['id_produit']) || empty($_GET['id_produit'])) {
    // On pourrait stocker un message d'erreur en session pour l'afficher sur la page de redirection
    $_SESSION['flash_message'] = "Aucun produit spécifié pour le signalement.";
    header("Location: facture.php");
    exit;
}

// 3. Appeler la fonction de signalement
$result = signalement();

$message = $result 
    ? "✅ Le produit a été signalé avec succès. Notre équipe examinera la situation." 
    : "❌ Une erreur est survenue lors du signalement. Il est possible que vous ayez déjà signalé ce produit.";

$colorClass = $result ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800";

// Redirection après 5 secondes
header("refresh:5;url=facture.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalement de produit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="max-w-md w-full bg-white shadow-lg rounded-2xl p-8 text-center">
        <div class="text-2xl mb-4"><?= $result ? '👍' : '🤔' ?></div>
        <h2 class="text-xl font-bold text-gray-800 mb-3">Statut du signalement</h2>
        <p class="<?= $colorClass ?> px-4 py-3 rounded-lg text-sm font-medium">
            <?= htmlspecialchars($message) ?>
        </p>
        <p class="text-gray-500 text-xs mt-4">
            Vous allez être redirigé vers la page des factures dans 5 secondes.
        </p>
    </div>

</body>
</html>