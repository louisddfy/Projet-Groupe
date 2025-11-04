<?php
session_start();
require_once("../utilisateur/function.php");
$produit = getProduct();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des catégories</title>
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
  <br>
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">📋 Liste des produits</h1>

        <?php if (empty($produit)) : ?>
            <p class="text-center text-gray-600">Aucun produit enregistrée pour le moment.</p>
        <?php else : ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Nom du Produit</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">ID Catégorie</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Description</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Prix</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Image</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-gray-50">
                        <?php foreach ($produit as $pod) : ?>
                            <tr class="hover:bg-gray-100 transition">
                                <td class="px-6 py-3"><?= htmlspecialchars($pod['nom_produit']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($pod['id_categorie']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($pod['description']) ?></td>
                                <td class="px-6 py-3"><?= htmlspecialchars($pod['prix']) ?></td>
                                <td class="px-6 py-3">
                                    <?php if (!empty($pod['image'])): ?>
                                        <img src="../vendeur/<?= htmlspecialchars($pod['image']) ?>" alt="Product Image" style="max-width:100px; max-height:100px;">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="mt-8 text-center">
            <a href="../Gestionnaire/Acceuil.php" class="text-gray-600 hover:underline">⬅ Retour à l’accueil</a>
        </div>
    </div>

    <footer class="mt-10 text-gray-500 text-sm">
        &copy; <?= date('Y') ?> Ma Boutique – Gestion des Produits
    </footer>

</body>
</html>
