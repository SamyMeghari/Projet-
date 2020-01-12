<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');

  $args = new RequestParameters("post");
  $args->defineInt('id');

  if (!$args->isValid()){
    produceError(implode(' ',$args->getErrorMessages()));
    return;
  }

  try{
    $data = new DataLayer();
    $res = $data->deletePost($_SESSION['ident'],$args->id);
    if ($res)produceResult($args->id);
    else produceError("ce post n'existe pas");
  } catch(PDOException $e){
    produceError($e->getMessage());
  }



?>
