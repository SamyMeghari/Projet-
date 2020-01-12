<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');

  $args = new RequestParameters();
  $args->defineString("mail");
  $args->defineString("description");
  $args->defineString("ville");
  $args->defineString("password");

  try{
    $data = new DataLayer();
    $user = $data->updateProfil($_SESSION['ident'],$args->mail,$args->description,$args->ville,$args->password);
    if ($user) produceResult($user);
    else produceError("utilisateur non trouvé");
  } catch(PDOException $e){
    produceError($e->getMessage());
  }


?>
