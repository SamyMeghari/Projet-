<?php

$dataUtilisateur = "";
if (isset($utilisateur))
   $dataUtilisateur = 'data-utilisateur="'.htmlentities(json_encode($utilisateur)).'"';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <meta charset="UTF-8" />
 <meta name="author" content="Boumahdi Samy, Happe Josué">
 <title>Connexion</title>
 <link rel="stylesheet" href="style/style.css" />
 <script src="js/fetchUtils.js"></script>
 <script src="js/gestionConnecte.js"></script>
 <script src="js/gestionLogin.js"></script>
</head>
<?php
  echo "<body $dataUtilisateur>";
  require("lib/common_menu.php")
?>
  <section class="deconnecte" id="connexion">
    <form method="POST" action="services/login.php"  id="form_login">
     <fieldset>
      <legend>Se connecter avec un compte déjà existant</legend>
      <label for="pseudo">Pseudo :</label>
      <input type="text" name="pseudo" id="pseudo" required="" autofocus=""/></br>
      <label for="password">Mot de passe :</label>
      <input type="password" name="password" id="password" required="required" /></br>
      <button type="submit" name="valid">Connexion</button></br>
     </fieldset>
    </form>
    <div class="error"></div>
  </section>
  <section class="deconnecte" id="inscription">
    <form method="POST" action="services/createUtilisateur.php" id="form_create">
      <fieldset>
        <legend> S'inscire et créer un nouveau compte </legend>
        <label for="pseudo"> Pseudo du compte :</label>
        <input type="text" name="pseudo" id="pseudo" required=""/></br>
        <label for="password"> Mot de passe :</label>
        <input type="password" name="password" id="password" required="required" /></br>
        <button type="submit" name="valid">S'inscrire</button></br>
      </fieldset>
    </form>
    <div class="error"></div>
  </section>
  <section class="connecte" id="deconnexion">
    <p id="deco">Se déconnecter ?<p>
      <button id="logout">Déconnexion</button>
  </section>
</body>
</html>
