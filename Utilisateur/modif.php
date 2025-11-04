<?php
session_start();

require_once("function.php");
require_once("../Connect/db.php");
if(!isset($_SESSION['connectUser'])) {
    header("Location: connexion.php");
    exit;
}

// Si le bouton Déconnexion est cliqué
if(isset($_POST['deconnexion'])){
    deconnectDB(); // détruit la session et redirige
}

$user = $_SESSION['connectUser']; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    
    <title>Modifier mon compte</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Modifier mon compte</h2>
</header>

        <form class="space-y-6" action="#" method="post" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                </div>

                <!-- Prénom -->
                <div>
                    <label class="block text-sm font-medium mb-1">Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                </div>

                <!-- Adresse -->
                <div>
                    <label class="block text-sm font-medium mb-1">Adresse</label>
                    <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-sm font-medium mb-1">Numéro de téléphone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nouveau mot de passe</label>
                    <input type="password" name="motdepasse" placeholder="Laisser vide pour garder l'actuel"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="text-center mt-6">
                <button type="submit" name="submit"
                    class="px-8 py-3 bg-gray-800 text-white rounded-lg font-semibold hover:bg-gray-700 transition duration-300 shadow-md">
                    Mettre à jour mon profil
                </button>
            </div>
            <div class="mt-6">
            <a href="../Utilisateur/Acceuil.php" class="text-gray-600 hover:underline">⬅ Retour à l’accueil</a>
        </div>
        </form>

        <!-- Message de résultat -->
        <?php
        if(isset($_POST['submit'])){
            $data = $_POST;
            $hasError = false;
            if(empty($data['motdepasse'])){
                $data['motdepasse'] = $user['motdepasse'];
            }

            if(!$hasError){
                if(modifUser($data)){
                    echo "<p class='text-green-600 text-center mt-4 font-semibold'>✅ Profil mis à jour avec succès</p>";
                    header("Refresh:2; url=modif.php");
                } else {
                    echo "<p class='text-red-600 text-center mt-4 font-semibold'>❌ Erreur lors de la mise à jour</p>";
                }
            }
        }
        ?>
    </div>

</body>
</html>