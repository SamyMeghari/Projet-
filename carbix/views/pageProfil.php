<?php

$dataUtilisateur = "";
if (isset($utilisateur))
   $dataUtilisateur = 'data-utilisateur="'.htmlentities(json_encode($utilisateur)).'"';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <meta name="author" content="Boumahdi Samy, Happe Josué">
 <title>Mon Profil</title>
 <link rel="stylesheet" href="style/style.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/gestionConnecte.js"></script>
 <script src="js/gestionProfil.js"></script>
</head>
<?php
  echo "<body $dataUtilisateur>";
  require("lib/common_menu.php")
?>
<section class="connecte" id="profil">
  <h2 id="pseudo"></h2>
  <div id="infoProfil"></div>
</section>
<section class="connecte" id="custom_profil">
  <form method="POST" action="services/updateProfil.php" id="form_profil">
    <fieldset>
      <legend> Modifiez votre profil </legend>
      <label for="mail">Mon e-mail : </label>
      <input type="text" name="mail" id="mail"/><br/>
      <label for="ville">Ma ville : </label>
      <input type="text" name="ville" id="ville"/><br/>
      <label for="password">Mon nouveau mot de passe : </label>
      <input type="text" name="password" id="password"/><br/>
      <label for="description">Ma présentation : </label>
      <textarea rows="5" cols="90" name="description" id="description"></textarea><br/><br/>
      <button type="submit" name="valid">Enregistrer les modifications</button></br>
    </fieldset>
  </form>
  <div id="error"></div>
</body>
</html>
