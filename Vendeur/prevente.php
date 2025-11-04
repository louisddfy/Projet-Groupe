<?php
session_start();
require_once("../Utilisateur/function.php");
$message = "";

$produit = getProduct();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Aucun produit sélectionné.</p>";
    exit;
}

$id_produit = (int)$_GET['id'];

if (isset($_POST['submit'])) {
    $data = $_POST;
    $data['id_produit'] = $id_produit;

    if (addPrevente($data)) {
        $message = "<p class='text-green-600 text-center mt-4 font-semibold'>✅ Prévente créée avec succès</p>";
    } else {
        $message = "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Erreur lors de la création de la prévente</p>";
    }
}   
$user = $_SESSION['connectUser'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d’une prévente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.6s ease-out both; }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-100 via-white to-gray-200 flex flex-col items-center justify-start text-gray-900">
    
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
        <a href="support.php" class="relative px-2 py-1 text-gray-700 font-medium hover:text-blue-600 transition-colors">
            Support
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
    <div class="fade-in w-full max-w-3xl bg-white shadow-2xl rounded-3xl p-10 border border-gray-200 relative overflow-hidden">
        <!-- Effet décoratif -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-gray-100 rounded-full blur-3xl opacity-40"></div>

        <!-- Titre -->
        <h2 class="text-3xl font-extrabold text-center mb-8 text-gray-800 tracking-tight">
            🎟️ Création d'une prévente
        </h2>

        <!-- Formulaire -->
        <form action="#" method="post" enctype="multipart/form-data" class="space-y-6 relative z-10">

            <!-- Sélection produit -->
            <div class="fade-in">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Produit</label>
                <select name="id_produit" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-gray-700 focus:outline-none transition-all duration-300 hover:border-gray-500">
                        <option value="">Produit à préventes</option>
                        <?php foreach ($produit as $prod): ?>
                            <option value="<?= htmlspecialchars($prod['id_produit']) ?>" <?= ($prod['id_produit'] == $id_produit) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prod['nom_produit']) ?> (ID: <?= htmlspecialchars($prod['id_produit']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
            </div>

            <!-- Date limite -->
            <div class="fade-in delay-[100ms]">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Fin de la prévente</label>
                <input type="date" name="date_limite" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-gray-700 focus:outline-none transition-all duration-300 hover:border-gray-500">
            </div>

            <!-- Nombre à vendre -->
            <div class="fade-in delay-[200ms]">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Nombre à vendre</label>
                <input type="number" name="nombre_min" required min="1"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-gray-700 focus:outline-none transition-all duration-300 hover:border-gray-500">
            </div>

            <!-- Prix prévente -->
            <div class="fade-in delay-[300ms]">
                <label class="block text-sm font-semibold mb-2 text-gray-700">Prix prévente (€)</label>
                <input type="number" name="prix_prevente" required step="0.01" min="0"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-gray-700 focus:outline-none transition-all duration-300 hover:border-gray-500">
            </div>

            <!-- Bouton de soumission -->
            <div class="text-center pt-4 fade-in delay-[400ms]">
                <button type="submit" name="submit"
                    class="px-10 py-3 bg-gray-800 text-white rounded-xl font-semibold text-lg shadow-md hover:shadow-lg hover:bg-gray-700 transform hover:-translate-y-1 transition-all duration-300">
                    ✨ Créer la prévente
                </button>
            </div>

            <!-- Lien retour -->
            <div class="mt-6 text-center fade-in delay-[500ms]">
                <a href="../Vendeur/produits.php" class="text-gray-600 hover:text-gray-900 hover:underline transition">
                    ⬅ Retour aux produits
                </a>
            </div>
        </form>

        <!-- Message -->
        <div class="mt-6 text-center fade-in">
            <?= $message ?>
        </div>
    </div>

</body>
</html>