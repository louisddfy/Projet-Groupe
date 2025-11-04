<?php

require '../Connect/db.php';
session_start();
function switchUserType() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pdo = connectDB();
        if ($pdo === null) return;

        $type = $_POST['type'];
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $adresse = htmlspecialchars($_POST['adresse'] ?? '');
        $phone = htmlspecialchars($_POST['phone'] ?? '');
        $mdp = password_hash($_POST['motdepasse'], PASSWORD_BCRYPT);
        $adresse_liv = htmlspecialchars($_POST['adresse_liv'] ?? '');
        $adresse_fac = htmlspecialchars($_POST['adresse_fac'] ?? '');
        $siret = htmlspecialchars($_POST['siret']);
        $adresse_entreprise = htmlspecialchars($_POST['adresse_entreprise']);
        $email_entreprise = htmlspecialchars($_POST['email_pro']);
        $nom_entreprise = htmlspecialchars($_POST['nom_entreprise']);

        if ($type === "Client") {
            $sql = "INSERT INTO Utilisateur (nom, prenom, adresse, email, phone, motdepasse) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $adresse, $email, $phone, $mdp]);

            $id_user = $pdo->lastInsertId();
            $stmt2 = $pdo->prepare("INSERT INTO Client (id_user, adresse_liv, adresse_fac) VALUES (?, ?, ?)");
            $stmt2->execute([$id_user, $adresse_liv, $adresse_fac]);

        } elseif ($type === "vendeur") {
            $sql1 = "INSERT INTO Utilisateur (nom, prenom, adresse, email, phone, motdepasse) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([$nom, $prenom, $adresse, $email, $phone, $mdp]);

            $id_user = $pdo->lastInsertId();

            $sql2 = "INSERT INTO Vendeur (id_user, nom_entreprise, siret, adresse_entreprise, email_pro) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$id_user, $nom_entreprise, $siret, $adresse_entreprise, $email_entreprise]);
        }
    }   echo '<div class="max-w-2xl mx-auto mt-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl text-center animate-fade-in">
            ✅ Votre inscription a bien été prise en compte !
          </div>';
}
function connectUser($data){
    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return false;
    }
    
    $email = htmlspecialchars($data['email']);
    $mdp = $data['motdepasse'];
    
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($mdp, $user['motdepasse'])) {
        $_SESSION['connectUser'] = $user;
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['motdepasse'] = $user['motdepasse'];
        
        $stmtVendeur = $pdo->prepare("SELECT * FROM Vendeur WHERE id_user = ?");
        $stmtVendeur->execute([$user['id_user']]);
        $vendeur = $stmtVendeur->fetch(PDO::FETCH_ASSOC);
        
        // Vérifier si le vendeur est bloqué en consultant la table `bloquer`
        if ($vendeur) {
            $stmtBlock = $pdo->prepare("SELECT COUNT(*) FROM bloquer WHERE id_vendeur = ?");
            $stmtBlock->execute([$user['id_user']]);
            if ($stmtBlock->fetchColumn() > 0) {
                header("Location: ../vendeur/litige.php");
                exit();
            }
        }

        if ($vendeur) {
            header("Location: ../vendeur/acceuil.php");
            exit();
        }

        if ($vendeur) {
            header("Location: ../vendeur/acceuil.php");
            exit();
        }
        
        $stmtClient = $pdo->prepare("SELECT * FROM Client WHERE id_user = ?");
        $stmtClient->execute([$user['id_user']]);
        $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
        
        if ($client) {
            header("Location: ../utilisateur/acceuil.php");
            exit();
        }

        $stmtgestionnaire = $pdo->prepare("SELECT * FROM Gestionnaire WHERE id_user =?");
        $stmtgestionnaire->execute([$user['id_user']]);
        $gestionnaire = $stmtgestionnaire->fetch(PDO::FETCH_ASSOC);

        if($gestionnaire) {
            header("Location: ../gestionnaire/acceuil.php");
            exit();

        }






        header("Location: acceuil.php");
        exit();
        
    } else {
        return false;
    }
}
function displayUserProfile(){
    if(!isset($_SESSION['id_user'])){
        echo "User not logged in.";
        return;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return;
    }


    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<h1>Profil de " . htmlspecialchars($user['nom']) . "</h1>";
        echo "<p>Prénom: " . htmlspecialchars($user['prenom']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
        echo "<p>Adresse: " . htmlspecialchars($user['adresse']) . "</p>";
        echo "<p>Téléphone: " . htmlspecialchars($user['phone']) . "</p>";
    } else {
        echo "User not found.";
    }
}
function modifUser($data){
    $user = $_SESSION['connectUser'];
    $pdo = connectDB();
    
    if(!$pdo){
        return false;
    }
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try{
        // Si le mot de passe n'est pas vide, on le hash, sinon on garde l'ancien
        if(!empty($data['motdepasse']) && $data['motdepasse'] !== $user['motdepasse']){
            $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_DEFAULT);
        } else {
            $data['motdepasse'] = $user['motdepasse'];
        }

        $req = "UPDATE Utilisateur SET nom=?, prenom=?, adresse=?, phone=?, email=?, motdepasse=? WHERE email=?";
        $stmt = $pdo->prepare($req);
        
        $values = [
            $data['nom'],
            $data['prenom'], 
            $data['adresse'],
            $data['phone'],
            $data['email'],
            $data['motdepasse'],
            $user['email'],
        ];

        $result = $stmt->execute($values);
        
        if(!$result){
            print_r($stmt->errorInfo());
            deconnectDB($pdo);
            return false;
        }
        else{
            // Mettre à jour la session
            $stmt_select = $pdo->prepare("SELECT * FROM Utilisateur WHERE email=?");
            $stmt_select->execute([$data['email']]);
            if($stmt_select->rowCount() > 0) {
                $_SESSION['connectUser'] = $stmt_select->fetch(PDO::FETCH_ASSOC);
            }

            return true;
        }
    }
    catch(PDOException $e){
        echo $e->getMessage();
        deconnectDB($pdo);
        return false;
    }
}
function uservendeur(){
    if(!isset($_SESSION['id_user'])){
        return false;
    }
    
    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM Vendeur WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $vendeur = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $vendeur !== false;
}
function userclient(){
    if(!isset($_SESSION['id_user'])){
        return false;
    }
    
    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM Client WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $client !== false;
}
function usergestion(){
    if(!isset($_SESSION['id_user'])){
        echo "User not logged in.";
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM Gestionnaire WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $gestion = $stmt->fetch(PDO::FETCH_ASSOC);

    return $gestion !== false;
}
function uploadImage($file) {
    // Vérifier s'il y a une erreur d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur d'upload : " . $file['error'] . "<br>";
        return false;
    }

    // Dossier de destination
    $uploadDir = "uploads/";
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            echo "Impossible de créer le dossier uploads/<br>";
            return false;
        }
    }

    // Vérifier les permissions du dossier
    if (!is_writable($uploadDir)) {
        echo "Le dossier uploads/ n'est pas accessible en écriture<br>";
        return false;
    }

    // Récupérer l'extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Extensions autorisées
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp','heic'];
    
    if (!in_array($extension, $allowedExtensions)) {
        echo "Extension non autorisée<br>";
        return false;
    }

    // Vérifier que c'est bien une image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        echo "Le fichier n'est pas une image valide<br>";
        return false;
    }

    // Générer un nom unique
    $newFileName = uniqid('img_', true) . '.' . $extension;
    $targetFile = $uploadDir . $newFileName;

    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $targetFile;
    } else {
        echo "Échec de move_uploaded_file()<br>";
        echo "Fichier temporaire : " . $file['tmp_name'] . "<br>";
        echo "Destination : " . $targetFile . "<br>";
        echo "Le fichier temporaire existe ? " . (file_exists($file['tmp_name']) ? "OUI" : "NON") . "<br>";
        return false;
    }
}
function addProduct($data){
    if(!isset($_SESSION['id_user'])){
        echo "User not logged in.";
        return false;
    }
    if(!uservendeur()){
        echo "Seulement les vendeurs peuvent ajouter des produits.";
        return false;
    }
    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return false;
    }
    
    $nom_produit = htmlspecialchars($data['nom_produit']);
    $lib_categorie = htmlspecialchars($data['lib']);
    $description = htmlspecialchars($data['description']);
    $prix = intval($data['prix']);
    $image = htmlspecialchars($data['image']);
    $id_vendeur = $_SESSION['id_user'];
    $id_categorie = $data['id_categorie'];
    
    $sql1 = "INSERT INTO Produit (nom_produit, id_categorie, description, prix, image, id_vendeur) 
             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt1 = $pdo->prepare($sql1);
    $result = $stmt1->execute([$nom_produit, $id_categorie, $description, $prix, $image, $id_vendeur]);
    
    if($result){
        echo "Produit ajouté avec succès.";
        return true;
    } else {
        echo "Erreur lors de l'ajout du produit.";
        return false;
    }
}
function getCategories() {

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return [];
    }

    $stmt = $pdo->query("SELECT id_categorie, lib FROM Categorie");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $categories;
}
function addCat($data){
     if(!isset($_SESSION['id_user'])){
        echo "User not logged in.";
        return false;
    }
 if(!usergestion()){
        echo "Seulement les gestionnaires peuvent ajouter des produits.";
        return false;
    }
    $pdo = connectDB();
    if ($pdo === null) {
    echo "Database connection failed.";
    return false;
    }
    $id_gestionnaire = $_SESSION['id_user'];
    $lib_categorie = htmlspecialchars($data['lib']);

    $sql1 = "INSERT INTO Categorie (id_gestionnaire, lib) VALUES (?, ?)";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$id_gestionnaire, $lib_categorie]);
     if ($stmt1) {
        return true; // ✅ Très important
    } else {
        return false;
    }

}
function deleteCategory($id) {
    if (!usergestion()) {
        return "forbidden";
    }
    $pdo = connectDB();
    if ($pdo === null) return false;
    $stmt = $pdo->prepare("SELECT * FROM Categorie WHERE id_categorie = ?");
    $stmt->execute([$id]);
    $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categorie) {
        return "notfound";
    }

    $delete = $pdo->prepare("DELETE FROM Categorie WHERE id_categorie = ?");
    if ($delete->execute([$id])) {
        return true;
    } else {
        return false;
    }
}
function updateCategory($id, $lib) {

    if (!usergestion()) {
        return "forbidden";
    }
    $pdo = connectDB();
    if ($pdo === null) return false;

    // Vérifier si la catégorie existe
    $stmt = $pdo->prepare("SELECT * FROM Categorie WHERE id_categorie = ?");
    $stmt->execute([$id]);
    $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categorie) {
        return "notfound";
    }

    // Vérifier que le lib n'est pas vide
    $lib = trim($lib);
    if ($lib === "") {
        return "empty";
    }

    // Mettre à jour la catégorie
    $update = $pdo->prepare("UPDATE Categorie SET lib = ? WHERE id_categorie = ?");
    if ($update->execute([$lib, $id])) {
        return true;
    } else {
        return false;
    }
}

function getProduct() {

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return [];
    }

    $stmt = $pdo->query("SELECT * FROM Produit ");
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $product;
}

function getVendeur() {
    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return [];
    }

    try {
        $sql = "SELECT 
                    v.*,
                    CASE WHEN b.id_vendeur IS NOT NULL THEN 'bloque' ELSE 'actif' END AS statut
                FROM Vendeur v
                LEFT JOIN bloquer b ON v.id_user = b.id_vendeur";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des vendeurs avec statut : " . $e->getMessage());
        return [];
    }
}

function getProductV() {
    if (!isset($_SESSION['id_user'])) {
        echo "Session expirée ou non connectée.";
        return false;
    }

    $pdo = connectDB();
    if (!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }

    try {
        $id_vendeur = (int) $_SESSION['id_user'];

        $req = "
            SELECT p.id_produit, p.nom_produit, p.description, p.prix, p.image, 
                   p.id_vendeur, p.created_at, p.updated_at,
                   c.lib AS categorie
            FROM produit p
            INNER JOIN categorie c ON p.id_categorie = c.id_categorie
            WHERE p.id_vendeur = :id_vendeur
        ";

        $stmt = $pdo->prepare($req);
        $stmt->execute(['id_vendeur' => $id_vendeur]);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produits;
    } 
    catch (PDOException $e) {
        echo "Erreur lors de la récupération des produits : " . $e->getMessage();
        return false;
    }
}
function addprevente($data) {
         if(!isset($_SESSION['id_user'])){
        echo "User not logged in.";
        return false;
    }
 if(!uservendeur()){
        echo "Seulement les vendeurs peuvent créer des preventes.";
        return false;
    }
    $pdo = connectDB();
    if ($pdo === null) {
    echo "Database connection failed.";
    return false;
    }
    $id_vendeur = $_SESSION['id_user'];
    $date_limite = $data['date_limite'];
    $nb_min = $data['nombre_min'];
    $statut = "Non Publiée";
    $prix_prevente = $data['prix_prevente'];
    $id_produit = $data['id_produit'];


    $sql1 = "INSERT INTO prevente (date_limite, nombre_min, statut, prix_prevente, id_produit) VALUES ( ?, ?, ?, ?, ?)";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$date_limite, $nb_min,$statut,$prix_prevente,$id_produit]);
     if ($stmt1) {
        return true;
    } else {
        return false;
    }

}
function modifProduit($data) {
    if (!isset($_SESSION['id_user'])) {
        echo "Utilisateur non connecté.";
        return false;
    }

    $pdo = connectDB();
    if (!$pdo) {
        echo "Erreur de connexion à la base.";
        return false;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmtCheck = $pdo->prepare("SELECT * FROM Produit WHERE id_produit = ? AND id_vendeur = ?");
        $stmtCheck->execute([$data['id_produit'], $_SESSION['id_user']]);
        $produit = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            echo "❌ Produit introuvable ou non autorisé.";
            return false;
        }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = uploadImage($_FILES['image']);
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                $data['image'] = $produit['image'];
            }
        } else {
            $data['image'] = $produit['image']; 
        }
        $req = "UPDATE Produit 
                SET nom_produit = ?, description = ?, image = ?, prix = ? 
                WHERE id_produit = ? AND id_vendeur = ?";
        $stmt = $pdo->prepare($req);

        $values = [
            htmlspecialchars($data['nom_produit']),
            htmlspecialchars($data['description']),
            htmlspecialchars($data['image']),
            (float) $data['prix'],
            (int) $data['id_produit'],
            (int) $_SESSION['id_user']
        ];

        $result = $stmt->execute($values);

        if (!$result) {
            print_r($stmt->errorInfo());
            return false;
        }

        $stmtReload = $pdo->prepare("SELECT * FROM Produit WHERE id_produit = ?");
        $stmtReload->execute([$data['id_produit']]);
        $newProduit = $stmtReload->fetch(PDO::FETCH_ASSOC);

        return $newProduit ?: true;

    } catch (PDOException $e) {
        echo "Erreur SQL : " . $e->getMessage();
        return false;
    }
}
function deleteProduct($id) {
    $pdo = connectDB();
    if ($pdo === null) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }

    // Vérifie que le produit existe avant suppression
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        echo "Produit introuvable.";
        return false;
    }

    // Supprime aussi le fichier image s’il existe
    if (!empty($produit['image']) && file_exists("../" . $produit['image'])) {
        unlink("../" . $produit['image']);
    }

    // Supprime le produit dans la base
    $deleteStmt = $pdo->prepare("DELETE FROM produit WHERE id_produit = ?");
    $success = $deleteStmt->execute([$id]);

    return $success;
}
function getprevente() {

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return [];
    }

    $stmt = $pdo->query("SELECT * FROM prevente");
    $prevente = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $prevente;
}
function postPrevente($id_prevente) {

    if (!is_numeric($id_prevente)) {
        return ['success' => false, 'message' => 'ID de prévente invalide.'];
    }

    $id_prevente = (int) $id_prevente;
    if (!uservendeur()) {
        return ['success' => false, 'message' => 'Accès refusé : seuls les vendeurs peuvent publier une prévente.'];
    }
    $pdo = connectDB();
    if ($pdo === null) {
        return ['success' => false, 'message' => 'Erreur de connexion à la base.'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE prevente SET statut = 'Active' WHERE id_prevente = ?");
        $result = $stmt->execute([$id_prevente]);

        if ($result) {
            return ['success' => true, 'message' => "Publiée avec succès !"];
        } else {
            return ['success' => false, 'message' => 'Échec de la publication.'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()];
    }
}
function getpreventeuser() {

    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return [];
    }

    $stmt = $pdo->query("SELECT * FROM prevente where statut = 'Active'");
    $prevente = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $prevente;
}
function participate($data) {
    if (!isset($_SESSION['id_user'])) {
        return ['success' => false, 'message' => "Utilisateur non connecté."];
    }

    if (!userclient()) {
        return ['success' => false, 'message' => "Seuls les clients peuvent participer."];
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return ['success' => false, 'message' => "Erreur de connexion à la base de données."];
    }

    $id_client = $_SESSION['id_user'];
    $id_prevente = $data['id_prevente'];
    $id_facture = $data['id_facture'] ?? null;

    try {
        $sqlCheck = "SELECT COUNT(*) FROM Participation WHERE id_client = ? AND id_prevente = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$id_client, $id_prevente]);

        if ($stmtCheck->fetchColumn() > 0) {
            return ['success' => false, 'message' => "Vous avez déjà participé à cette prévente."];
        }
        $sqlInsert = "INSERT INTO Participation (id_client, id_prevente, id_facture) VALUES (?, ?, ?)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $result = $stmtInsert->execute([$id_client, $id_prevente, $id_facture]);

        if ($result && $stmtInsert->rowCount() > 0) {
            return ['success' => true, 'message' => "Participation enregistrée avec succès !"];
        } else {
            return ['success' => false, 'message' => "Erreur lors de l'enregistrement de la participation."];
        }

    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Erreur SQL : " . $e->getMessage()];
    }
}
function nbparticipation($id_prevente) {
    $pdo = connectDB();
    if ($pdo === null) {
        echo "Database connection failed.";
        return 0;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) AS id_participation FROM Participation WHERE id_prevente = ?");
    $stmt->execute([$id_prevente]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (int)$result['id_participation'] : 0;
}
function getPreventeStatus($id_prevente) {
    $pdo = connectDB();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("SELECT statut FROM prevente WHERE id_prevente = ?");
        $stmt->execute([$id_prevente]);
        $statut = $stmt->fetchColumn();

        if ($statut === 'Active') {
            return 'active'; 
        } elseif (in_array($statut, ['Annulée','Facturée','Validée'])) {
            return 'terminee'; 
        } else {
            return 'non'; 
        }
    } catch (PDOException $e) {
        error_log("Erreur getPreventeStatus(): " . $e->getMessage());
        return false;
    }
}

function isParticipated($id_prevente) {
    if (!isset($_SESSION['id_user'])) {
        return false;
    }

    $pdo = connectDB();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Participation WHERE id_prevente = ? AND id_client = ?");
        $stmt->execute([$id_prevente, $_SESSION['id_user']]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur isParticipated(): " . $e->getMessage());
        return false;
    }
}

function isProductInPrevente($id_produit) {
    $pdo = connectDB();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prevente WHERE id_produit = ? AND statut = 'Active'");
        $stmt->execute([$id_produit]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur isProductInPrevente(): " . $e->getMessage());
        return false;
    }
}
function getpreventefactures() {
    $pdo = connectDB();
    if (!$pdo) return [];

    try {
        $stmt = $pdo->prepare("
            SELECT 
                p.id_prevente,
                p.date_limite,
                p.prix_prevente,
                p.statut,
                pr.id_produit,
                pr.nom_produit,
                pr.prix AS prix_produit,
                pr.description,
                v.nom_entreprise
            FROM Prevente p
            JOIN Produit pr ON pr.id_produit = p.id_produit
            JOIN Vendeur v ON v.id_user = pr.id_vendeur
            WHERE p.statut IN ('Facturée', 'Validée')
            ORDER BY p.updated_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getpreventefactures : " . $e->getMessage());
        return [];
    }
}

function passerEnFacturee($id_prevente) {
    $pdo = connectDB();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("SELECT statut FROM Prevente WHERE id_prevente = ?");
        $stmt->execute([$id_prevente]);
        $statut = $stmt->fetchColumn();

        if ($statut !== 'Validée') {
            return false;
        }
        $stmt = $pdo->prepare("
            UPDATE Prevente 
            SET statut = 'Facturée', updated_at = NOW()
            WHERE id_prevente = ?
        ");
        return $stmt->execute([$id_prevente]);
    } catch (PDOException $e) {
        error_log("Erreur passerEnFacturee : " . $e->getMessage());
        return false;
    }
}
function mesgainVendeur($id_vendeur) {

    $pdo = connectDB();
    if (!$pdo) return 0;

    try {
        $sql = "
            SELECT SUM(pv.prix_prevente) AS total_gain
            FROM Prevente pv
            INNER JOIN Produit pr ON pr.id_produit = pv.id_produit
            INNER JOIN Participation pa ON pa.id_prevente = pv.id_prevente
            WHERE pv.statut = 'Facturée'
              AND pr.id_vendeur = :id_vendeur
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_vendeur' => $id_vendeur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_gain'] ?? 0;

    } catch (PDOException $e) {
        error_log("Erreur mesgainVendeur: " . $e->getMessage());
        return 0;
    }
}
function getFacture($id_prevente) {
    $pdo = connectDB();
    if (!$pdo) {
        return null;
    }
    
    try {
        $sql = "
            SELECT 
                p.id_prevente,
                p.date_limite,
                p.statut,
                pr.description,
                p.prix_prevente,
                pr.nom_produit,
                pr.prix AS prix_produit,
                v.nom_entreprise,
                v.email_pro AS vendeur_email
            FROM prevente p
            JOIN produit pr ON p.id_produit = pr.id_produit
            JOIN vendeur v ON pr.id_vendeur = v.id_user
            WHERE p.id_prevente = ?
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_prevente]);
        $facture = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$facture) {
            return null;
        }
        if ($facture['statut'] === 'Validée') {
            passerEnFacturee($id_prevente);
            $facture['statut'] = 'Facturée';
        }
        
        return $facture;
        
    } catch (PDOException $e) {
        error_log("Erreur getFacture : " . $e->getMessage());
        return null;
    }
}

function signalement() {
    if (!isset($_SESSION['id_user'])) {
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }

    $id_user = $_SESSION['id_user'];
    $id_produit = $_GET['id_produit'] ?? null;

    if (!$id_produit) {
        return false;
    }

    // Vérifier si l'utilisateur a déjà signalé ce produit
    $sqlCheck = "SELECT COUNT(*) FROM Signaler WHERE id_user = ? AND id_produit = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$id_user, $id_produit]);
    $alreadySignaled = $stmtCheck->fetchColumn();

    if ($alreadySignaled > 0) {
        return false; // L'utilisateur a déjà signalé ce produit
    }

    $sql = "INSERT INTO Signaler (id_user, id_produit, date_signal) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$id_user, $id_produit]);

    return $result;
}

function cancelSignalement($id_produit, $id_user) {
    if (!isset($id_user) || !isset($id_produit)) {
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }

    try {
        // Vérifier si le signalement existe et appartient bien à l'utilisateur
        $sqlCheck = "SELECT COUNT(*) FROM Signaler WHERE id_user = ? AND id_produit = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$id_user, $id_produit]);
        if ($stmtCheck->fetchColumn() == 0) {
            return false; // Signalement non trouvé ou n'appartient pas à l'utilisateur
        }

        // Supprimer le signalement
        $sqlDelete = "DELETE FROM Signaler WHERE id_user = ? AND id_produit = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $result = $stmtDelete->execute([$id_user, $id_produit]);

        return $result;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'annulation du signalement : " . $e->getMessage());
        return false;
    }
}

function getUserSignalements($id_user) {
    if (!isset($id_user)) {
        return [];
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return [];
    }

    try {
        $sql = "SELECT s.id_produit, s.date_signal, p.nom_produit, p.description, p.image
                FROM Signaler s
                JOIN Produit p ON s.id_produit = p.id_produit
                WHERE s.id_user = ? ORDER BY s.date_signal DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des signalements : " . $e->getMessage());
        return [];
    }
}

function getAllSignalements() {
    if (!usergestion()) {
        return [];
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return [];
    }

    try {
        $sql = "SELECT 
                    s.id_user, 
                    s.id_produit, 
                    s.date_signal, 
                    p.nom_produit, 
                    p.id_vendeur,
                    u_reporter.prenom AS reporter_prenom,
                    u_reporter.nom AS reporter_nom,
                    v.nom_entreprise AS vendeur_nom,
                    (SELECT COUNT(*) FROM Signaler WHERE id_produit = p.id_produit) AS nb_signalements,
                    (SELECT COUNT(DISTINCT pa.id_client) FROM Participation pa JOIN Prevente pv ON pa.id_prevente = pv.id_prevente WHERE pv.id_produit = p.id_produit) AS nb_participants
                FROM Signaler s
                JOIN Produit p ON s.id_produit = p.id_produit
                JOIN Utilisateur u_reporter ON s.id_user = u_reporter.id_user
                LEFT JOIN Vendeur v ON p.id_vendeur = v.id_user 
                ORDER BY s.date_signal DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de tous les signalements : " . $e->getMessage());
        return [];
    }
}

function deleteSignalement($id_user, $id_produit) {
    if (!usergestion()) {
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }

    try {
        $sql = "DELETE FROM Signaler WHERE id_user = ? AND id_produit = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id_user, $id_produit]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du signalement : " . $e->getMessage());
        return false;
    }
}

function toggleVendeurStatus($id_vendeur) {
    if (!usergestion()) {
        return false;
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return false;
    }

    try {
        // Vérifier si le vendeur est déjà bloqué
        $stmtCheck = $pdo->prepare("SELECT id_bloquer FROM bloquer WHERE id_vendeur = ?");
        $stmtCheck->execute([$id_vendeur]);
        $isBlocked = $stmtCheck->fetch();

        if ($isBlocked) {
            // Si bloqué, on le débloque en supprimant l'entrée
            $stmtToggle = $pdo->prepare("DELETE FROM bloquer WHERE id_vendeur = ?");
            return $stmtToggle->execute([$id_vendeur]);
        } else {
            // Si non bloqué, on le bloque en insérant une entrée
            $id_gestionnaire = $_SESSION['id_user']; // On suppose que le gestionnaire est connecté
            $stmtToggle = $pdo->prepare(
                "INSERT INTO bloquer (id_gestionnaire, id_vendeur, date_blocage) VALUES (?, ?, NOW())"
            );
            return $stmtToggle->execute([$id_gestionnaire, $id_vendeur]);
        }
    } catch (PDOException $e) {
        error_log("Erreur lors du changement de statut du vendeur : " . $e->getMessage());
        return false;
    }
}

function submitLitige($id_vendeur, $raison) {
    if (empty(trim($raison))) {
        return ['success' => false, 'message' => 'Veuillez fournir une raison pour votre demande.'];
    }

    $pdo = connectDB();
    if ($pdo === null) {
        return ['success' => false, 'message' => 'Erreur de connexion à la base de données.'];
    }

    try {
        // Vérifier si une demande n'est pas déjà en cours
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM debloquer WHERE id_vendeur = ? AND date_deblocage IS NULL");
        $stmtCheck->execute([$id_vendeur]);
        if ($stmtCheck->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Une demande de déblocage est déjà en cours.'];
        }

        // Requête INSERT explicite pour s'assurer que les autres champs sont NULL
        $stmt = $pdo->prepare("INSERT INTO debloquer (id_vendeur, raison, id_gestionnaire, date_deblocage) VALUES (?, ?, NULL, NULL)");

        if ($stmt->execute([$id_vendeur, htmlspecialchars($raison)])) {
            return ['success' => true, 'message' => 'Votre demande a bien été envoyée.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de l\'envoi de votre demande.'];
        }
    } catch (PDOException $e) {
        error_log("Erreur submitLitige : " . $e->getMessage());
        return ['success' => false, 'message' => 'Une erreur technique est survenue.'];
    }
}

function isLitigePending($id_vendeur) {
    $pdo = connectDB();
    if ($pdo === null) return false;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM debloquer WHERE id_vendeur = ? AND date_deblocage IS NULL");
        $stmt->execute([$id_vendeur]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur isLitigePending : " . $e->getMessage());
        return false;
    }
}

function getPendingLitiges() {
    if (!usergestion()) return [];

    $pdo = connectDB();
    if ($pdo === null) return [];

    try {
        // Requête corrigée et finale
        $sql = "SELECT d.id_debloquer, d.id_vendeur, d.raison, u.nom, u.prenom, u.email
                FROM debloquer d
                JOIN Utilisateur u ON d.id_vendeur = u.id_user
                WHERE d.date_deblocage IS NULL OR d.date_deblocage = '0000-00-00 00:00:00'
                ORDER BY d.id_debloquer ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getPendingLitiges : " . $e->getMessage());
        return [];
    }
}

function resolveLitige($id_debloquer, $id_vendeur) {
    if (!usergestion()) return false;

    $pdo = connectDB();
    if ($pdo === null) return false;

    $id_gestionnaire = $_SESSION['id_user'];

    try {
        $pdo->beginTransaction();

        // 1. Mettre à jour la table `debloquer`
        $stmtUpdate = $pdo->prepare("UPDATE debloquer SET id_gestionnaire = ?, date_deblocage = NOW() WHERE id_debloquer = ?");
        $stmtUpdate->execute([$id_gestionnaire, $id_debloquer]);

        // 2. Supprimer l'entrée de la table `bloquer` pour débloquer le vendeur
        $stmtDelete = $pdo->prepare("DELETE FROM bloquer WHERE id_vendeur = ?");
        $stmtDelete->execute([$id_vendeur]);

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erreur resolveLitige : " . $e->getMessage());
        return false;
    }
}

function refuseLitige($id_debloquer) {
    if (!usergestion()) return false;

    $pdo = connectDB();
    if ($pdo === null) return false;

    try {
        // On supprime simplement la demande de la table `debloquer`.
        // Le vendeur reste dans la table `bloquer`.
        $stmt = $pdo->prepare("DELETE FROM debloquer WHERE id_debloquer = ?");
        $success = $stmt->execute([$id_debloquer]);

        return $success;

    } catch (PDOException $e) {
        error_log("Erreur refuseLitige : " . $e->getMessage());
        return false;
    }
}


function getResolvedLitiges() {
    if (!usergestion()) return [];

    $pdo = connectDB();
    if ($pdo === null) return [];
    try {
        // Requête simplifiée pour récupérer TOUS les litiges traités
        // On sélectionne TOUT sans condition pour le débogage.
        $sql = "SELECT * FROM debloquer ORDER BY id_debloquer DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur getResolvedLitiges : " . $e->getMessage());
        return [];
    }
}
?>