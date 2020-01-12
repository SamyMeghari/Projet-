<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');

  $args = new RequestParameters();
  $args->defineNonEmptyString("station");
  $args->defineNonEmptyString("titre");
  $args->defineNonEmptyString("contenu");

  if (!$args->isValid()){
    produceError(implode(' ',$args->getErrorMessages()));
    return;
  }

  try{
    $data = new DataLayer();
    $id = $data->createPost($_SESSION['ident'],$args->station,$args->titre,$args->contenu);
    produceResult($id);
  } catch(PDOException $e){
    produceError($e->getMessage());
  }

?>
