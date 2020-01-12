<?php
//Auteurs : Happe Josué et Boumahdi Samy
spl_autoload_register(function ($className) {
    include ("lib/{$className}.class.php");
});
session_name('s_carbix');
session_start();
if (isset($_SESSION['ident'])){
    $utilisateur = $_SESSION['ident'];
}

date_default_timezone_set ('Europe/Paris');
$data = new DataLayer();
require ('views/pageConnexion.php');
?>
