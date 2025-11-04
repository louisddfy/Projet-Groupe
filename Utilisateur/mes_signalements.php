<?php
session_start();
require_once("function.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connectUser'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['connectUser'];
$id_user = $_SESSION['id_user'];
$message = '';
$messageType = '';

// Gérer l'annulation d'un signalement
if (isset($_GET['cancel_id_produit']) && !empty($_GET['cancel_id_produit'])) {
    $id_produit_to_cancel = (int)$_GET['cancel_id_produit'];
    if (cancelSignalement($id_produit_to_cancel, $id_user)) {
        $message = "✅ Le signalement a été annulé avec succès.";
        $messageType = 'success';
    } else {
        $message = "❌ Une erreur est survenue lors de l'annulation du signalement ou le signalement n'existe pas.";
        $messageType = 'error';
    }
    // Rediriger pour éviter la soumission multiple via rafraîchissement
    header("Location: mes_signalements.php?msg=" . urlencode($message) . "&type=" . urlencode($messageType));
    exit;
}

// Afficher les messages après redirection
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $messageType = htmlspecialchars($_GET['type']);
}

// Récupérer les signalements de l'utilisateur
$signalements = getUserSignalements($id_user);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Signalements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 via-white to-gray-200 flex flex-col items-center text-gray-900">

    <header class="w-full bg-white shadow-lg py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="bg-blue-600 text-white font-bold text-xl rounded-full h-12 w-12 flex items-center justify-center shadow-md">
                <?= strtoupper(substr($user['prenom'],0,2)) ?>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Espace Client</h1>
                <p class="text-gray-500 text-sm">Bienvenue, <span class="font-semibold"><?= htmlspecialchars($user['prenom']) ?></span> !</p>
            </div>
        </div>

        <nav class="flex items-center gap-6">
              <a href="Acceuil.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Acceuil
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="facture.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Mes Factures
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="produits.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Liste des Préventes
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="affichage.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Profil
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
       <a href="mes_signalements.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Mes Signalements
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="../utilisateur/connexion.php" class="px-5 py-2 bg-gray-800 text-white rounded-xl font-medium hover:bg-gray-700 transition-colors duration-300 shadow-md hover:shadow-lg">
            Déconnexion
        </a> </nav>
    </header>

    <script>
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('mouseenter', () => {
                const span = link.querySelector('span');
                span.style.width = '100%';
            });
            link.addEventListener('mouseleave', () => {
                const span = link.querySelector('span');
                // Keep current page link underlined
                if(link.href !== window.location.href){
                    span.style.width = '0';
                }
            });
            if(link.href === window.location.href){
                link.classList.add('text-blue-600');
                const span = link.querySelector('span');
                span.style.width = '100%';
            }
        });
    </script>

    <main class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Mes Signalements</h2>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (empty($signalements)): ?>
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-16 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">🔔</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun signalement en cours</h3>
                <p class="text-gray-500">Vous n'avez signalé aucun produit pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden divide-y divide-gray-200">
                <?php foreach ($signalements as $signalement): ?>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-5 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4 w-full md:w-3/4">
                            <?php if (!empty($signalement['image'])): ?>
                                <img src="../vendeur/<?= htmlspecialchars($signalement['image']) ?>" alt="Image produit" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">📦</div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($signalement['nom_produit']) ?></p>
                                <p class="text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars($signalement['description']) ?></p>
                                <p class="text-xs text-gray-500 mt-1">Signalé le : <?= date('d/m/Y H:i', strtotime($signalement['date_signal'])) ?></p>
                            </div>
                        </div>
                        <a href="mes_signalements.php?cancel_id_produit=<?= urlencode($signalement['id_produit']) ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir annuler ce signalement ?')"
                           class="mt-4 md:mt-0 inline-block bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition-colors duration-300 shadow-sm">
                            Annuler le signalement
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="mt-auto w-full border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
            <p class="text-gray-600 text-sm">
                &copy; <?= date('Y') ?> Drink & Co — Tous droits réservés
            </p>
            <p class="text-gray-400 text-xs mt-2">Plateforme de préventes en ligne</p>
        </div>
    </footer>

</body>
</html>