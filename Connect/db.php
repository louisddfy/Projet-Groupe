<?php

session_start();
function connectDB(){
	try{
	$pdo = new PDO("mysql:host=localhost;dbname=vente_groupe","root","mysql");
	return $pdo;
	} catch(PDOException $e){
		return null;
	}
	
}

function deconnectDB(){
    session_destroy();
    header("Location: ../utilisateur/connexion.php");
    exit();
}

?>
