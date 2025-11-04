<?php
session_start();

require_once("../utilisateur/function.php");
require_once("../Connect/db.php");

if (!isset($_SESSION['connectUser'])) {
    header("Location: connexion.php");
    exit;
}
if (isset($_POST['deconnexion'])) {
    deconnectDB(); 
}
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Aucun produit sélectionné.</p>";
    exit;
}

$id_produit = (int)$_GET['id'];
$user = $_SESSION['connectUser'];
$pdo = connectDB();
$stmt = $pdo->prepare("SELECT * FROM Produit WHERE id_produit = ? AND id_vendeur = ?");
$stmt->execute([$id_produit, $_SESSION['id_user']]);
$pod = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pod) {
    echo "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Produit introuvable ou non autorisé.</p>";
    exit;
}
$categories = getCategories();

if (isset($_POST['submit'])) {
    $data = $_POST;
    $data['id_produit'] = $id_produit;

    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage($_FILES['image']);
        if ($imagePath) {
            $data['image'] = $imagePath;
        } else {
            $data['image'] = $pod['image'];
        }
    } else {
        $data['image'] = $pod['image'];
    }

    if (modifProduit($data)) {
        echo "<p class='text-green-600 text-center mt-4 font-semibold'>✅ Produit mis à jour avec succès</p>";
        header("Refresh:2; url=listproduct.php");
        exit;
    } else {
        echo "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Erreur lors de la mise à jour</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier un produit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <nav class="flex items-center gap-6">
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
  <br>

<div class="w-full max-w-3xl bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Modifier un produit</h2>

    <form class="space-y-6" method="post" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Nom Produit</label>
                <input type="text" name="nom_produit" value="<?= htmlspecialchars($pod['nom_produit']) ?>" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <input type="text" name="description" value="<?= htmlspecialchars($pod['description']) ?>" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" name="image"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                <?php if (!empty($pod['image'])): ?>
                    <p class="mt-2 text-sm text-gray-600">Image actuelle :</p>
                    <img src="<?= htmlspecialchars($pod['image']) ?>" alt="Image du produit" class="mt-1 h-24 rounded-lg border">
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Prix (€)</label>
                <input type="number" name="prix" value="<?= htmlspecialchars($pod['prix']) ?>" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Catégorie</label>
                <select name="id_categorie" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>" <?= ($cat['id_categorie'] == $pod['id_categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['lib']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="text-center mt-6">
            <button type="submit" name="submit"
                class="px-8 py-3 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-700 transition duration-300 shadow-md">
                Mettre à jour le produit
            </button>
        </div>

        <div class="mt-6">
            <a href="../Vendeur/Acceuil.php" class="text-gray-600 hover:underline">⬅ Retour à l’accueil</a>
        </div>
    </form>
</div>

</body>
</html>