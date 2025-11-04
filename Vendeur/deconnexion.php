<?php
session_start();
require_once("../utilisateur/function.php");

deconnectDB();

header("Location: ../utilisateur/connexion.php");
exit();
?>