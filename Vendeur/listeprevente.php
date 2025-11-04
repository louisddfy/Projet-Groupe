<?php
session_start();
require_once("../utilisateur/function.php");

$produit = getProductV();
$vendeur = getVendeur();
$prevente = getPrevente();
$prevv = getPreventeuser();
$user = $_SESSION['connectUser'];

$showNotification = false;
$notificationMessage = '';
$notificationType = '';

if(isset($_POST['publier'])){
    if(isset($_POST['id_prevente'])){
        $res = postPrevente($_POST['id_prevente']);
        $showNotification = true;
        $notificationMessage = $res['message'];
        $notificationType = $res['success'] ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de vente – Produits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideIn 0.3s ease-out forwards; }
        .toast-exit { animation: slideOut 0.3s ease-in forwards; }
    </style>
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
    <!-- Notification Toast -->
    <?php if($showNotification): ?>
    <div id="toast" class="fixed top-6 right-6 z-50 toast-enter">
        <div class="<?= $notificationType === 'success' ? 'bg-green-500' : 'bg-red-500' ?> text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 min-w-[300px]">
            <span class="text-2xl"><?= $notificationType === 'success' ? '✅' : '❌' ?></span>
            <p class="font-semibold flex-1"><?= htmlspecialchars($notificationMessage) ?></p>
            <button onclick="closeToast()" class="text-white hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <script>
        function closeToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            setTimeout(() => toast.remove(), 300);
        }
        setTimeout(() => { closeToast(); }, 3000);
    </script>
    <?php endif; ?>

 <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-center gap-4 mb-8">
        <button class="filter-btn bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-semibold" data-statut="Tous">Tous</button>
        <button class="filter-btn bg-red-200 text-red-800 px-4 py-2 rounded-xl font-semibold" data-statut="Non Publiée">Non Publiée</button>
        <button class="filter-btn bg-green-200 text-green-800 px-4 py-2 rounded-xl font-semibold" data-statut="Active">Active</button>
        <button class="filter-btn bg-blue-200 text-blue-800 px-4 py-2 rounded-xl font-semibold" data-statut="Validée">Validée</button>
        <button class="filter-btn bg-gray-300 text-gray-800 px-4 py-2 rounded-xl font-semibold" data-statut="Annulée">Annulée</button>
        <button class="filter-btn bg-yellow-300 text-yellow-900 px-4 py-2 rounded-xl font-semibold" data-statut="Facturée">Facturée</button>
        
    </div>

   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[repeat(3,minmax(350px,1fr))] gap-8">
    <?php foreach($prevente as $prev): 
        $pod = reset(array_filter($produit, fn($p) => $p['id_produit'] == $prev['id_produit']));
        if(!$pod) continue;
        $vend = reset(array_filter($vendeur, fn($v) => $v['id_user'] == $pod['id_vendeur']));
        $nbparticipation = nbparticipation($prev['id_prevente']);
    ?>
        <div class="prevente-card group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col overflow-hidden border border-gray-100 hover:border-blue-300 " data-statut="<?= htmlspecialchars($prev['statut']) ?>">
            <div class="relative overflow-hidden">
                <div class="absolute top-4 left-4 z-20">
                    <?php $colorClass = ($nbparticipation >= $prev['nombre_min']) ? 'bg-green-900' : 'bg-red-800'; ?>
                    <div class="<?= $colorClass ?> text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                        <span class="text-sm">👥</span>
                        <span><?= htmlspecialchars($nbparticipation) ?></span>
                    </div>
                </div>
                <div class="absolute top-4 right-4 z-20">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm 
<?=
    $prev['statut'] == 'Active' ? 'bg-green-300 text-white' : 
    ($prev['statut'] == 'Annulée' ? 'bg-red-700 text-white' : 
    ($prev['statut'] == 'Validée' ? 'bg-blue-500 text-white' : 
    ($prev['statut'] == 'Facturée' ? 'bg-yellow-400 text-white' : 'bg-gray-400 text-white')))
?>">
    <?= htmlspecialchars($prev['statut']) ?>
</span>
                    </span>
                </div>

                <?php if(!empty($pod['image'])): ?>
                    <img src="../vendeur/<?= htmlspecialchars($pod['image']) ?>" alt="Image produit" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                <?php else: ?>
                    <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-400 text-5xl">📦</div>
                <?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <div class="p-6 flex-1 flex flex-col">
                <h3 class="font-bold text-xl text-gray-900 mb-2 line-clamp-1"><?= htmlspecialchars($pod['nom_produit']) ?></h3>
                <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-1"><?= htmlspecialchars($pod['description']) ?></p>

                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 bg-gradient-to-br from-white-100 to-purple-800 rounded-full flex items-center justify-center text-gray-800 font-semibold text-sm"><?= strtoupper(substr($vend['nom_entreprise'],0,2)) ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500">Vendeur</p>
                        <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($vend['nom_entreprise']) ?></p>
                    </div>
                </div>

                <div class="mb-4">
    <p class="text-xs text-gray-500 mb-1">Prix</p>
    <div class="flex items-baseline gap-3">
        <p class="text-3xl font-bold bg-gray-800 bg-clip-text text-transparent">
            <?= htmlspecialchars($prev['prix_prevente']) ?> €
        </p>
        <p class="text-sm text-red-400 line-through">
            <?= htmlspecialchars($pod['prix']) ?> €
        </p>
    </div>
</div>

                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4 space-y-2 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 flex items-center gap-1.5"><span class="text-base">📅</span> Date limite</span>
                        <span class="font-semibold text-gray-900">: <?=htmlspecialchars($prev['date_limite']) ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 flex items-center gap-1.5"><span class="text-base">🎯</span> Objectif min</span>
                        <span class="font-semibold text-gray-900">: <?= htmlspecialchars($prev['nombre_min']) ?> unités</span>
                    </div>
                </div>

                
         <?php $preventeStatus = getPreventeStatus($prev['id_prevente']); ?>
<form method="POST" class="inline w-full">
    <input type="hidden" name="id_prevente" value="<?= $prev['id_prevente'] ?>">
    <button 
        type="submit" 
        name="publier" 
        <?= $preventeStatus !== 'non' ? 'disabled' : '' ?>
        class="w-full font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-md flex items-center justify-center gap-2
        <?php
            if ($preventeStatus === 'active') {
                echo 'bg-gradient-to-l from-green-400 to-green-600 text-white cursor-not-allowed';
            } elseif ($preventeStatus === 'terminee') {
                echo 'bg-gradient-to-l from-red-400 to-red-600 text-white cursor-not-allowed';
            } else {
                echo 'bg-gradient-to-l from-gray-600 to-gray-900 hover:from-green-900 hover:to-green-900 text-white hover:shadow-lg';
            }
        ?>">
        <span class="text-lg">
            <?= $preventeStatus === 'active' ? '✔ Déjà publié' : ($preventeStatus === 'terminee' ? '❌ Vente terminée' : '📢 Publier') ?>
        </span>
    </button>
</form>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</main>

<script>
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.prevente-card');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const statut = btn.getAttribute('data-statut');
            cards.forEach(card => {
                if(statut === 'Tous' || card.dataset.statut === statut){
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<footer class="">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center">
            <p class="text-gray-600 text-sm">
                &copy; <?= date('Y') ?> Drink & Co — Tous droits réservés
            </p>
            <p class="text-gray-400 text-xs mt-2">
                Plateforme de préventes en ligne
            </p>
        </div>
    </div>
</footer>

</body>
</html>