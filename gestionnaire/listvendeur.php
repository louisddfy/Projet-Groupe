<?php
session_start();
require_once("../utilisateur/function.php");

$message = '';
$messageType = '';

if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id_vendeur'])) {
    $id_vendeur = (int)$_GET['id_vendeur'];
    if (toggleVendeurStatus($id_vendeur)) {
        $message = "✅ Le statut du vendeur a été mis à jour.";
        $messageType = 'success';
    } else {
        $message = "❌ Erreur lors de la mise à jour du statut.";
        $messageType = 'error';
    }
    header("Location: listvendeur.php?msg=" . urlencode($message) . "&type=" . $messageType);
    exit;
}

if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
    $messageType = htmlspecialchars($_GET['type']);
}
$user = $_SESSION['connectUser'];
$vendeur = getVendeur();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des vendeurs</title>
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
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-6xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">📋 Liste des vendeurs</h1>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (empty($vendeur)) : ?>
            <p class="text-center text-gray-600">Aucun vendeur enregistrée pour le moment.</p>
        <?php else : ?>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Entreprise</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">SIRET</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Adresse</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($vendeur as $ven) : ?>
                            <tr class="hover:bg-gray-100 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($ven['id_user']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($ven['nom_entreprise']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($ven['siret']) ?></td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 max-w-xs"><?= htmlspecialchars($ven['adresse_entreprise']) ?></td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500"><?= htmlspecialchars($ven['email_pro']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $ven['statut'] === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= htmlspecialchars(ucfirst($ven['statut'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="?action=toggle_status&id_vendeur=<?= $ven['id_user'] ?>" 
                                       class="px-4 py-2 rounded-md text-xs font-semibold shadow-sm transition-colors duration-200 <?= $ven['statut'] === 'actif' ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-green-500 text-white hover:bg-green-600' ?>">
                                        <?= $ven['statut'] === 'actif' ? 'Bloquer' : 'Débloquer' ?>
                                    </a>
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
        &copy; <?= date('Y') ?> Ma Boutique – Gestion des Vendeurs
    </footer>

</body>
</html>
