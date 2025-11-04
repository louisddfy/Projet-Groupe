<!DOCTYPE html>
<html lang="en">
<?php
require_once("function.php");
?>
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-white to-gray-200 text-gray-900 relative">

  <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login</h2>
    
    <form class="space-y-6" action="#" method="post">
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium mb-2 text-gray-700">Email</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email"
          class="w-full px-4 py-2 rounded-lg bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-gray-500 focus:outline-none">
      </div>

      <!-- Password -->
      <div>
        <label for="motdepasse" class="block text-sm font-medium mb-2 text-gray-700">Mot de passe</label>
        <input type="password" id="motdepasse" name="motdepasse" placeholder="Entrez votre mot de passe"
          class="w-full px-4 py-2 rounded-lg bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-gray-500 focus:outline-none">
      </div>

      <!-- Submit -->
      <button type="submit" name="submit"
        class="w-full py-3 mt-4 rounded-lg bg-gray-800 text-white hover:bg-gray-700 transition duration-300 font-semibold shadow-md">
        Se connecter
      </button>

      <!-- Link -->
      <p class="text-center text-sm mt-4 text-gray-600">
        Pas encore de compte ?
        <a href="inscription.php" class="text-gray-800 font-medium hover:underline">S'inscrire</a>
      </p>
    </form>
  </div>

  <?php
  if(isset($_POST['submit'])){
    array_pop($_POST);
    if(!connectUser($_POST)){
      echo "<div class='absolute bottom-4 w-full text-center'>
              <p class='text-red-600 font-semibold bg-red-100 border border-red-300 px-4 py-2 rounded-lg inline-block shadow-md'>
                ❌ Identifiants incorrects
              </p>
            </div>";
    }
  }
  ?>
</body>
</html>