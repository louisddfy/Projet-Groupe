<?php
session_start();
require_once("../utilisateur/function.php");

// Si l'utilisateur n'est pas connecté ou n'est pas un vendeur, on le redirige.
if (!isset($_SESSION['id_user']) || !uservendeur()) {
    header("Location: ../utilisateur/connexion.php");
    exit;
}

$id_vendeur = $_SESSION['id_user'];
$message = '';

if (isset($_POST['submit_litige'])) {
    $raison = $_POST['raison'] ?? '';
    $result = submitLitige($id_vendeur, $raison);
    $message = $result['message'];
}

// On vérifie si une demande est déjà en cours pour ne pas afficher le formulaire si c'est le cas.
$litigeEnCours = isLitigePending($id_vendeur);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Bloqué</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-red-50 flex items-center justify-center">

    <div class="max-w-2xl w-full bg-white shadow-2xl rounded-2xl p-10 text-center border-t-4 border-red-500">
        <div class="text-6xl mb-4">🚫</div>
        <h1 class="text-3xl font-bold text-red-800 mb-3">Accès Restreint</h1>
        <p class="text-gray-700 mb-6">
            Votre compte vendeur a été temporairement bloqué suite à des signalements répétés.
        </p>

        <?php if ($message): ?>
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php $litigeEnCours = true; // On met à jour la variable pour masquer le formulaire après soumission ?>
        <?php endif; ?>

        <?php if ($litigeEnCours): ?>
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-100">
                <p class="font-semibold">Votre demande de déblocage est en cours de traitement.</p>
                <p>Notre équipe de modération l'examinera dans les plus brefs délais.</p>
                <a href="deconnexion.php" class="inline-block mt-4 bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                    Retour à la page de connexion
                </a>
            </div>
        <?php else: ?>
            <form action="litige.php" method="post" class="mt-8 text-left">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contester cette décision</h2>
                <div>
                    <label for="raison" class="block text-sm font-medium text-gray-700 mb-2">Veuillez expliquer pourquoi vous pensez que cette décision devrait être réexaminée :</label>
                    <textarea id="raison" name="raison" rows="5" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Expliquez votre situation..."></textarea>
                </div>
                <button type="submit" name="submit_litige" class="w-full mt-4 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition duration-300 font-semibold shadow-md">
                    Envoyer ma demande
                </button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>