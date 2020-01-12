<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');
  $login = $_SESSION['ident'];
  try{
    $data = new DataLayer();
    $mesPosts = $data->findMesPosts($login);
    produceResult(['date'=>date('Y/d/m H:i:s'),'liste'=>$mesPosts]);
  } catch(PDOException $e){
    produceError($e->getMessage());
  }


?>
