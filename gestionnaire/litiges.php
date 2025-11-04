<?php
session_start();
require_once("../utilisateur/function.php");

if (!usergestion()) {
    header("Location: ../utilisateur/connexion.php");
    exit;
}

$message = '';
$messageType = '';

if (isset($_GET['action']) && $_GET['action'] === 'resolve' && isset($_GET['id_debloquer']) && isset($_GET['id_vendeur'])) {
    $id_debloquer = (int)$_GET['id_debloquer'];
    $id_vendeur = (int)$_GET['id_vendeur'];

    if (resolveLitige($id_debloquer, $id_vendeur)) {
        $message = "✅ Le vendeur a été débloqué et le litige est marqué comme résolu.";
        $messageType = 'success';
    } else {
        $message = "❌ Une erreur est survenue lors du traitement du litige.";
        $messageType = 'error';
    }
    header("Location: litiges.php?msg=" . urlencode($message) . "&type=" . urlencode($messageType));
    exit;
}

if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
    $messageType = htmlspecialchars($_GET['type']);
}
$user = $_SESSION['connectUser'];
$litiges = getPendingLitiges();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Litiges</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 via-white to-gray-200 flex flex-col items-center justify-start text-gray-900">

  <header class="w-full bg-white shadow-lg py-4 px-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="bg-blue-600 text-white font-bold text-xl rounded-full h-12 w-12 flex items-center justify-center shadow-md">
            <?= strtoupper(substr($user['prenom'],0,2)) ?>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Gestionnaire</h1>
            <p class="text-gray-500 text-sm">Bienvenue, <span class="font-semibold"><?= htmlspecialchars($user['prenom']) ?></span> !</p>
        </div>
    </div>

    <nav class="flex items-center gap-6">
        <a href="Acceuil.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Acceuil
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="categorie.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Ajout Catégorie
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="listcat.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Liste des catégories
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="listproduit.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Liste des produits
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
       <a href="listvendeur.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Listes des vendeurs
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="signalements.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Signalement
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="litiges.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Gestion des Litiges
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>

        <a href="../utilisateur/connexion.php" class="px-5 py-2 bg-gray-800 text-white rounded-xl font-medium hover:bg-gray-700 transition-colors duration-300 shadow-md hover:shadow-lg">
            Déconnexion
        </a>
    </nav>
  </header>

    <main class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Demandes de déblocage en attente</h2>
            <a href="historique_litiges.php" class="text-sm text-blue-600 hover:underline">Voir l'historique →</a>
        </div>
        
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (empty($litiges)): ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-5xl mb-4">👍</div>
                <h3 class="text-xl font-semibold text-gray-900">Aucun litige en attente</h3>
                <p class="text-gray-500 mt-2">Toutes les demandes ont été traitées.</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow overflow-hidden divide-y divide-gray-200">
                <?php foreach ($litiges as $litige): ?>
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($litige['prenom'] . ' ' . $litige['nom']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($litige['email']) ?></p>
                            </div>
                            <div class="flex gap-2">
                                <a href="?action=resolve&id_debloquer=<?= $litige['id_debloquer'] ?>&id_vendeur=<?= $litige['id_vendeur'] ?>"
                                   onclick="return confirm('Êtes-vous sûr de vouloir débloquer ce vendeur et résoudre ce litige ?')"
                                   class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                    Débloquer
                                </a>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
                            <p class="text-sm font-semibold text-gray-800 mb-1">Raison fournie par le vendeur :</p>
                            <p class="text-gray-700 text-sm whitespace-pre-wrap"><?= htmlspecialchars($litige['raison']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>