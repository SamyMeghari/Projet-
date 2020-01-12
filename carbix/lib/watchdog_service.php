<?php
//vérifie si l'utilisateur est connecté
require("lib/common_service.php");
require("lib/session_start.php");

if (isset($_SESSION['ident'])) return;

produceError("utilisateur non identifié");
exit();
?>
