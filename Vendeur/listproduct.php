<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once("../utilisateur/function.php");
$produit = getProductV();
$categories = getCategories();
$user = $_SESSION['connectUser'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <style>
        /* Wrapper principal DataTables */
        .dataTables_wrapper {
            padding: 0;
            font-family: inherit;
        }

        /* En-tête avec recherche et longueur */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        /* Labels */
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin: 0;
        }

        /* Select pour le nombre d'entrées */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem;
            appearance: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_length select:hover {
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            border-color: #1f2937;
            ring: 2px;
            ring-color: #1f2937;
            box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
        }

        /* Input de recherche */
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            width: 16rem;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_filter input:hover {
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #1f2937;
            box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
        }

        /* Info et pagination en bas */
        .dataTables_wrapper .dataTables_info {
            padding: 1.25rem;
            font-size: 0.875rem;
            color: #6b7280;
            float: left;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 1.25rem;
            float: right;
        }

        /* Boutons de pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.875rem;
            margin: 0 0.125rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white !important;
            color: #111827 !important;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
            transform: translateY(-1px);
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1f2937 !important;
            border-color: #1f2937 !important;
            color: white !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #111827 !important;
            border-color: #111827 !important;
            color: white !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Table principale */
        table.dataTable {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table.dataTable thead th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: white;
            background: #111827;
            border-bottom: none;
            position: relative;
        }

        /* Indicateurs de tri */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            cursor: pointer;
            padding-right: 2rem;
        }

        table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc::after {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
        }

        table.dataTable thead .sorting::before, {
            content: "⇅";
            opacity: 0.3;
        }

        table.dataTable thead .sorting_asc::after {
            content: "↑";
            opacity: 1;
        }

        table.dataTable thead .sorting_desc::after {
            content: "↓";
            opacity: 1;
        }

        /* Corps du tableau */
        table.dataTable tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        table.dataTable tbody tr {
            background: white;
            transition: background-color 0.2s;
        }

        table.dataTable tbody tr:hover {
            background: #f9fafb;
        }

        /* Suppression des bordures par défaut */
        table.dataTable.no-footer {
            border-bottom: none;
        }

        /* Clearfix pour les floats */
        .dataTables_wrapper::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Animation */
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(10px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        .fade-in { 
            animation: fadeIn 0.6s ease-out both; 
        }
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
    <div class="container mx-auto px-4 py-10 max-w-7xl">

        <!-- Contenu principal -->
      <main class="fade-in bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <?php if (empty($produit)) : ?>
        <div class="p-16 text-center">
            <p class="text-gray-500 text-lg">Aucun produit enregistré pour le moment.</p>
        </div>
    <?php else : ?>
        <div class="overflow-x-auto">
            <table id="produitsTable" class="w-full">
                <thead>
                    <tr>
                        <th>Nom du produit</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Vendeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produit as $pod) : ?>
                        <tr>
                            <td class="font-semibold text-gray-900">
                                <?= htmlspecialchars($pod['nom_produit']) ?>
                            </td>
                            <td class="text-gray-600">
                                <?= htmlspecialchars($pod['categorie']) ?>
                            </td>
                            <td class="text-gray-600 max-w-xs truncate">
                                <?= htmlspecialchars($pod['description']) ?>
                            </td>
                            <td class="font-bold text-green-600">
                                <?= htmlspecialchars($pod['prix']) ?> €
                            </td>
                            <td class="text-center">
                                <?php if (!empty($pod['image'])): ?>
                                    <img src="../vendeur/<?= htmlspecialchars($pod['image']) ?>" 
                                         alt="Produit" 
                                         class="w-16 h-16 object-cover rounded-lg mx-auto hover:scale-110 transition-transform duration-200">
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">Aucune image</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-gray-700 text-center">
                                <?= htmlspecialchars($pod['id_vendeur']) ?>
                            </td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <?php if (isProductInPrevente($pod['id_produit'])): ?>
                                        <button class="px-3 py-1.5 bg-gray-400 text-white text-xs font-medium rounded-md cursor-not-allowed">
                                            En prévente
                                        </button>
                                    <?php else: ?>
                                        <a href="modifpro.php?id=<?= htmlspecialchars($pod['id_produit']) ?>" 
                                           class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors text-center">
                                            Modifier
                                        </a>
                                        <a href="deleteproduct.php?id=<?= htmlspecialchars($pod['id_produit']) ?>" 
                                           class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition-colors text-center"
                                           onclick="return confirm('Êtes-vous sûr ?');">
                                            Supprimer
                                        </a>
                                        <a href="prevente.php?id=<?= htmlspecialchars($pod['id_produit']) ?>" 
                                           class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition-colors text-center">
                                            Prévente
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
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
    </div>

    <script>
      $(document).ready(function() {
        $('#produitsTable').DataTable({
          pageLength: 10,
          lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
          language: {
            search: "Rechercher :",
            lengthMenu: "Afficher _MENU_ produits",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ produits",
            infoEmpty: "Aucun produit",
            infoFiltered: "(filtré de _MAX_ produits)",
            paginate: { 
              previous: "Précédent", 
              next: "Suivant"
            },
            zeroRecords: "Aucun produit trouvé"
          },
          order: [[0, 'asc']],
          columnDefs: [
            { orderable: false, targets: [4, 6] }
          ]
        });
      });
    </script>
</body>
</html>