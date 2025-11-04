<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-8 px-4">
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex bg-gray-100 rounded-xl p-1 mb-6">
                <button 
                    id="btnUtilisateur" 
                    class="flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300 text-white bg-gradient-to-r from-blue-500 to-blue-600 shadow-md"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Client
                    </span>
                </button>
                <button 
                    id="btnVendeur" 
                    class="flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300 text-gray-600 hover:text-gray-800"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Vendeur
                    </span>
                </button>
            </div>


            <form id="formUtilisateur" method="post" class="animate-fade-in">
                <input type="hidden" name="type" value="Client">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nom *</label>
                        <input 
                            type="text" 
                            name="nom" 
                            placeholder="Votre nom" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Prénom *</label>
                        <input 
                            type="text" 
                            name="prenom" 
                            placeholder="Votre prénom" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Email *</label>
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="votre@email.com" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Adresse *</label>
                        <input 
                            type="text" 
                            name="adresse" 
                            placeholder="Votre adresse complète" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Adresse Livraison *</label>
                        <input 
                            type="text" 
                            name="adresse_liv" 
                            placeholder="Votre adresse de livraison" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Adresse Facturation *</label>
                        <input 
                            type="text" 
                            name="adresse_fac" 
                            placeholder="Votre adresse de facturation" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Téléphone *</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            placeholder="06 12 34 56 78" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Mot de passe *</label>
                        <input 
                            type="password" 
                            name="motdepasse" 
                            placeholder="Créez un mot de passe sécurisé" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full mt-8 py-4 px-6 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:ring-4 focus:ring-blue-300"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        S'inscrire comme Client
                    </span>
                </button>
            </form>

            <form id="formVendeur" method="post" class="hidden animate-fade-in">
                <input type="hidden" name="type" value="vendeur">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nom *</label>
                        <input 
                            type="text" 
                            name="nom" 
                            placeholder="Votre nom" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Prénom *</label>
                        <input 
                            type="text" 
                            name="prenom" 
                            placeholder="Votre prénom" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

               

                  <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Adresse *</label>
                        <input 
                            type="text" 
                            name="adresse" 
                            placeholder="Votre adresse complète" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                   <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">email *</label>
                        <input 
                            type="text" 
                            name="email" 
                            placeholder="Votre email" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>

                     <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Numéro de téléphone *</label>
                        <input 
                            type="text" 
                            name="phone" 
                            placeholder="Votre numéro de téléphone" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Mot de passe *</label>
                        <input 
                            type="password" 
                            name="motdepasse" 
                            placeholder="Créez un mot de passe sécurisé" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nom de la société *</label>
                        <input 
                            type="text" 
                            name="nom_entreprise" 
                            placeholder="Nom de votre entreprise" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Numéro SIRET *</label>
                        <input 
                            type="text" 
                            name="siret" 
                            placeholder="14 chiffres de votre SIRET" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Adresse de la société *</label>
                        <input 
                            type="text" 
                            name="adresse_entreprise" 
                            placeholder="Adresse complète de l'entreprise" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Email professionnel *</label>
                        <input 
                            type="email" 
                            name="email_pro" 
                            placeholder="contact@entreprise.com" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full mt-8 py-4 px-6 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:ring-4 focus:ring-purple-300"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        S'inscrire comme Vendeur
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 text-sm">
            <p>Déjà inscrit ? <a href="connexion.php" class="text-blue-600 hover:text-blue-800 font-semibold">Connectez-vous</a></p>
        </div>
    </div>

    <script>
        const btnUtilisateur = document.getElementById("btnUtilisateur");
        const btnVendeur = document.getElementById("btnVendeur");
        const formUtilisateur = document.getElementById("formUtilisateur");
        const formVendeur = document.getElementById("formVendeur");

        function switchToUser() {
            btnUtilisateur.classList.remove("text-gray-600", "hover:text-gray-800");
            btnUtilisateur.classList.add("text-white", "bg-gradient-to-r", "from-blue-500", "to-blue-600", "shadow-md");
            
            btnVendeur.classList.remove("text-white", "bg-gradient-to-r", "from-purple-500", "to-purple-600", "shadow-md");
            btnVendeur.classList.add("text-gray-600", "hover:text-gray-800");
            formUtilisateur.classList.remove("hidden");
            formVendeur.classList.add("hidden");
        }

        function switchToVendeur() {
            btnVendeur.classList.remove("text-gray-600", "hover:text-gray-800");
            btnVendeur.classList.add("text-white", "bg-gradient-to-r", "from-purple-500", "to-purple-600", "shadow-md");
            
            btnUtilisateur.classList.remove("text-white", "bg-gradient-to-r", "from-blue-500", "to-blue-600", "shadow-md");
            btnUtilisateur.classList.add("text-gray-600", "hover:text-gray-800");
            formVendeur.classList.remove("hidden");
            formUtilisateur.classList.add("hidden");
        }

        btnUtilisateur.addEventListener("click", switchToUser);
        btnVendeur.addEventListener("click", switchToVendeur);
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('transform', 'scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('transform', 'scale-105');
            });
        });
    </script>

    <?php
    require 'function.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $file_path = switchUserType();
    }
    ?>

</body>
</html>