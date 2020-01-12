<?php

$dataUtilisateur = "";
if (isset($utilisateur))
   $dataUtilisateur = 'data-utilisateur="'.htmlentities(json_encode($utilisateur)).'"';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <meta name="author" content="Boumahdi Samy, Happe Josué">
 <title>Mes Posts</title>
 <link rel="stylesheet" href="style/style.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/gestionConnecte.js"></script>
 <script src="js/gestionPosts.js"></script>
</head>
<?php
  echo "<body $dataUtilisateur>";
  require("lib/common_menu.php")
?>
  <section class="connecte" id="posts">
    <h2> Mes Posts </h2>
    <div id="message"></div>
    <div id="section_posts"></div>
  </section>
</body>
</html>
