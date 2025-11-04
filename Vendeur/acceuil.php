<?php
session_start();
require_once("../Utilisateur/function.php");

if(!isset($_SESSION['connectUser'])) {
    header("Location: ../utilisateur/connexion.php");
    exit;
}
$id_vendeur = $_SESSION['id_user']; // ou récupéré d’une autre manière
$gain = mesgainVendeur($id_vendeur);

$user = $_SESSION['connectUser'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 via-white to-gray-200 flex flex-col items-center justify-start text-gray-900">
    
  <header class="w-full bg-white shadow-lg py-4 px-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="bg-blue-600 text-white font-bold text-xl rounded-full h-12 w-12 flex items-center justify-center shadow-md">
            <?= strtoupper(substr($user['prenom'],0,2)) ?>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Espace Vendeur</h1>
            <p class="text-gray-500 text-sm">Bienvenue, <span class="font-semibold"><?= htmlspecialchars($user['prenom']) ?></span> !</p>
        </div>
    </div>

    <nav class="flex items-center gap-2">
        <a href="Acceuil.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Acceuil
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="produits.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Ajouts Produits
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="listproduct.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Liste des Produits
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="listeprevente.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Liste des Préventes
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="ventes.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Mes Ventes
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
        <a href="affichage.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Profil
            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-blue-600 transition-all"></span>
        </a>
 <div class="flex items-center gap-4 md:gap-6">
        <div class="flex items-center gap-2 bg-green-50 text-green-800 px-4 py-2 rounded-xl shadow-inner font-semibold hover:bg-green-100 transition duration-300">
            Gains : <?= number_format($gain, 2) . " €"; ?>
        </div>

        <a href="../utilisateur/connexion.php" class="px-5 py-2 bg-gray-800 text-white rounded-xl font-medium hover:bg-gray-700 transition-colors duration-300 shadow-md hover:shadow-lg">
            Déconnexion
        </a>
    </nav>
  </header>

  <script>
    document.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('mouseenter', () => {
            const span = link.querySelector('span');
            span.style.width = '100%';
        });
        link.addEventListener('mouseleave', () => {
            const span = link.querySelector('span');
            span.style.width = '0';
        });
        if(link.href === window.location.href){
            link.classList.add('text-blue-600');
            const span = link.querySelector('span');
            span.style.width = '100%';
        }
    });
  </script>


    <!-- Boutons principaux -->
    <div class="w-full max-w-4xl mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <a href="affichage.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">👤</div>
            <span class="font-semibold text-gray-800">Mon Profil</span>
        </a>

        <a href="modif.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">✏️</div>
            <span class="font-semibold text-gray-800">Modifier mon compte</span>
        </a>
        <a href="produits.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">✚</div>
            <span class="font-semibold text-gray-800">Ajouter Produits</span>
        </a>
        <a href="listproduct.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">📦</div>
            <span class="font-semibold text-gray-800">Liste des Produits</span>
        </a>
        <a href="listeprevente.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">📋</div>
            <span class="font-semibold text-gray-800">Mes Préventes</span>
        </a>
        <a href="listeprevente.php" class="bg-white shadow-lg rounded-2xl p-6 flex flex-col items-center justify-center hover:shadow-2xl transition duration-300">
            <div class="text-4xl mb-2">🪙</div>
            <span class="font-semibold text-gray-800">Mes Ventes</span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="mt-auto w-full text-center py-6 text-gray-500">
        &copy; <?= date("Y") ?> Mon Application. Tous droits réservés.
    </footer>

</body>
</html>