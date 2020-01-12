<?php

$dataUtilisateur = "";
if (isset($utilisateur))
   $dataUtilisateur = 'data-utilisateur="'.htmlentities(json_encode($utilisateur)).'"';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <meta name="author" content="Boumahdi Samy, Happe Josué">
 <title>Recherche de stations</title>
 <link rel="stylesheet" href="style/style.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/gestionConnecte.js"></script>
 <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
 <script src="js/gestionRecherche.js"></script>
</head>
<?php
  echo "<body $dataUtilisateur>";
  require("lib/common_menu.php")
?>
<section id="search">
  <h2>Incomplet, Ne fonctionne pas</h2><br/>
  <form action="" method="post" id="form_recherche">
    <fieldset id="recherche">
      <legend>Recherche des stations</legend>
      <label for="commune"> Commune : </label>
      <input name="commune" id="commune" type="text" required="required"><br/>
      <label for="rayon"> Rayon : </label>
      <input name="rayon" id="rayon" type="text" pattern="[0-9]+"/><br/>
      <label for="carburants"> Carburants voulus : </label><br/>
      <input name="carburants" id="carburants" type="checkbox" value="1">Gazole<br/>
      <input name="carburants" id="carburants" type="checkbox" value="2">SP95<br/>
      <input name="carburants" id="carburants" type="checkbox" value="3">E85<br/>
      <input name="carburants" id="carburants" type="checkbox" value="4">GPL<br/>
      <input name="carburants" id="carburants" type="checkbox" value="5">E10<br/>
      <input name="carburants" id="carburants" type="checkbox" value="6">SP98<br/>
      <br/>
        <button type="reset">Effacer</button>
        <button type="submit" name="valid" value="ok">Envoyer</button>
    </fieldset>
  </form>
</section>

</body>
</html>
