<?php

session_start();
require_once("../Utilisateur/function.php");
$message = "";

$categories = getCategories();
if(!isset($_SESSION['connectUser'])) {
    header("Location: connexion.php");
    exit;
}
$user = $_SESSION['connectUser'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
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
    <div class="w-full max-w-3xl bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Ajouter un produit</h2>

        <form action="#" method="post" enctype="multipart/form-data" class="space-y-6">
            <!-- Nom du produit -->
            <div>
                <label class="block text-sm font-medium mb-1">Nom du produit</label>
                <input type="text" name="nom_produit" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>
            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-medium mb-1">Catégorie</label>
                <select name="id_categorie" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                    <option value="">Sélectionner une catégorie</option>
                    <?php
                    foreach ($categories as $categorie) {
                        echo "<option value='" . htmlspecialchars($categorie['id_categorie']) . "'>" . htmlspecialchars($categorie['lib']) . "</option>";
                    }
                    ?>
                </select>
            </div>


            <!-- Description -->
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none"></textarea>
            </div>

            <!-- Prix -->
            <div>
                <label class="block text-sm font-medium mb-1">Prix (€)</label>
                <input type="number" step="0.01" name="prix" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>

            <!-- Image (fichier) -->
            <div>
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" name="image" accept="image/*" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>

            <!-- Bouton de soumission -->
            <div class="text-center mt-6">
                <button type="submit" name="submit"
                    class="px-8 py-3 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-700 transition duration-300 shadow-md">
                    Ajouter le produit
                </button>
            </div>
            <div class="mt-6">
            <a href="../Vendeur/Acceuil.php" class="text-gray-600 hover:underline">⬅ Retour à l’accueil</a>
        </div>
        </form>
    <div class="mt-4">
            <?= $message ?>
        </div>
                

</body>
</html>

<?php
if(isset($_POST['submit'])){
    $file = $_FILES["image"];
    $filePath = uploadImage($file);
    
    $data = $_POST;
    unset($data['submit']); 
    $data['image'] = $filePath; 
    
    if(addproduct($data)){
        $message = "<p class='text-green-600 text-center mt-4 font-semibold'>✅ Produit ajouté avec succès</p>";
    } else {
        $message = "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Erreur lors de l'ajout du produit</p>";
    }
}

?>