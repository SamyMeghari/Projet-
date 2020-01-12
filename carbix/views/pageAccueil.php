<?php

$dataUtilisateur = "";
if (isset($utilisateur))
   $dataUtilisateur = 'data-utilisateur="'.htmlentities(json_encode($utilisateur)).'"';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <meta name="author" content="Boumahdi Samy, Happe Josué">
 <title>Page d'accueil</title>
 <link rel="stylesheet" href="style/style.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/gestionConnecte.js"></script>
 <script src="js/bestStations.js"></script>
</head>
<?php
  echo "<body $dataUtilisateur>";
  require("lib/common_menu.php")
?>
  <section id="best">
    <h2>Les 10 meilleures stations</h2>
    <div id="error"></div>
    <div id="best_stations"
  </section>
</body>
</html>
