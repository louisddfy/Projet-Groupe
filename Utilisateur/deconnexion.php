<?php
session_start();
require_once("function.php");

deconnectDB();

header("Location: connexion.php");
exit();
?>