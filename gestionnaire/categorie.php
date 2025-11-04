<?php
session_start();
require_once("../utilisateur/function.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    if (addCat($data)) {
        $message = "<p class='text-green-600 text-center mt-4 font-semibold'>✅ Catégorie ajoutée avec succès</p>";
    } else {
        $message = "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Erreur lors de l'ajout de la catégorie</p>";
    }
}
$user = $_SESSION['connectUser'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
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
  <br>   <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">➕ Ajouter une catégorie</h1>

        <form method="POST" action="#" class="flex flex-col gap-4">
            <div class="text-left">
                <label for="lib" class="block text-sm font-semibold text-gray-700 mb-1">Nom de la catégorie :</label>
                <input type="text" name="lib" id="lib" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:outline-none bg-gray-50">
            </div>

            <button type="submit"
                class="w-full py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300">
                Ajouter la catégorie
            </button>
        </form>

        <?= $message ?>
        <div class="mt-6">
            <a href="../Gestionnaire/ListCat.php" class="text-gray-600 hover:underline">⬅ Liste des catégories</a>
        </div>

        <div class="mt-6">
            <a href="../Gestionnaire/Acceuil.php" class="text-gray-600 hover:underline">⬅ Retour à l’accueil</a>
        </div>
    </div>

    <footer class="mt-10 text-gray-500 text-sm">
        &copy; <?= date('Y') ?> Ma Boutique – Gestion des catégories
    </footer>

</body>
</html>