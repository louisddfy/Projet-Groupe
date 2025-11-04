<?php
session_start();
require_once("../utilisateur/function.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$produit = getProductV();
$vendeur = getVendeur();
$prevente = getpreventefactures();
$signaler = signalement();

$user = $_SESSION['connectUser'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client – Mes Factures</title>
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
  <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-10">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
          📋 Mes Factures
        </h2>
        <div class="flex flex-wrap gap-3 items-center">

          <select id="filter-date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="recent">Plus récentes d'abord</option>
            <option value="ancien">Plus anciennes d'abord</option>
          </select>
        </div>
      </div>
    </div>

    <?php if (empty($prevente)) : ?>
      <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-16 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">📄</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune facture disponible</h3>
        <p class="text-gray-500">Vous n’avez pas encore de factures générées.</p>
      </div>
    <?php else : ?>
      <div id="factures-list" class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden divide-y divide-gray-200">
        <?php foreach ($prevente as $pre) : ?>
          <div class="facture-row flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-5 hover:bg-gray-50 transition-colors"
             data-date="<?= htmlspecialchars($pre['date_limite']) ?>">
            <div class="flex flex-col w-full md:w-3/4 gap-1 text-sm text-gray-700">
              <div class="flex flex-wrap gap-x-8">
                <p><span class="font-semibold text-gray-900">N° Prévente :</span> <?= htmlspecialchars($pre['id_prevente']) ?></p>
                <p><span class="text-gray-500">Produit :</span> <span class="font-medium"><?= htmlspecialchars($pre['nom_produit']) ?></span></p>
                <p><span class="text-gray-500">Prix :</span> <?= htmlspecialchars($pre['prix_produit']) ?> €</p>
                <p><span class="text-gray-500">Facturé :</span> <?= htmlspecialchars($pre['prix_prevente']) ?> €</p>
              </div>

              <div class="flex flex-wrap gap-x-8">
                <p><span class="text-gray-500">Entreprise :</span> <?= htmlspecialchars($pre['nom_entreprise']) ?></p>
                <p><span class="text-gray-500">Date :</span> <?= htmlspecialchars($pre['date_limite']) ?></p>
                <p>
                  <span class="text-gray-500">Statut :</span>
                  <span class="<?php 
                    echo match($pre['statut']) {
                      'Facturée' => 'text-yellow-600 font-semibold',
                      'Validée' => 'text-blue-600 font-semibold',
                      'Annulée' => 'text-red-600 font-semibold',
                      'Active' => 'text-green-600 font-semibold',
                      default => 'text-gray-600'
                    };
                  ?>">
                    <?= htmlspecialchars($pre['statut']) ?>
                  </span>
                </p>
              </div>
              <p class="text-gray-500 mt-1"><span class="font-medium">Description :</span> <?= htmlspecialchars($pre['description']) ?></p>
            </div>

            <a href="signalement.php?id_produit=<?= urlencode($pre['id_produit']) ?>"
   class="mt-4 md:mt-0 inline-block bg-red-600 text-white px-5 py-2 rounded-xl font-medium hover:bg-red-500 transition-colors duration-300 shadow-sm">
  Signaler
</a>

<a href="telecharger_facture.php?id_prevente=<?= urlencode($pre['id_prevente']) ?>"
   class="mt-4 md:mt-0 inline-block bg-blue-600 text-white px-5 py-2 rounded-xl font-medium hover:bg-blue-500 transition-colors duration-300 shadow-sm">
  Télécharger
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

  <script>
    const dateSelect = document.getElementById('filter-date');
    const factures = Array.from(document.querySelectorAll('.facture-row'));

    function applyFilters() {
      const order = dateSelect.value;

      const sorted = factures.sort((a, b) => {
        const da = new Date(a.dataset.date);
        const db = new Date(b.dataset.date);
        return order === "recent" ? db - da : da - db;
      });

      const container = document.getElementById('factures-list');
      sorted.forEach(f => container.appendChild(f));
    }

    statutSelect.addEventListener('change', applyFilters);
    dateSelect.addEventListener('change', applyFilters);
  </script>

  

</body>
</html>