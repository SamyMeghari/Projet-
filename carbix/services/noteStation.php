<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');

  $args = new RequestParameters();
  $args->defineNonEmptyString("id");
  $args->defineInt("global",['min_range'=>0,'max_range'=>5]);
  $args->defineInt("accueil",['min_range'=>0,'max_range'=>5]);
  $args->defineInt("prix",['min_range'=>0,'max_range'=>5]);
  $args->defineInt("service",['min_range'=>0,'max_range'=>5]);

  if(!$args->isValid()){
    produceError(implode(' ',$args->getErrorMessages()));
    return;
  }

  try{
    $data = new DataLayer();
    $station = $data->noteStation($_SESSION['ident'],$args->id,$args->global,$args->accueil,$args->prix,$args->service);
    if ($station) produceResult($station);
    else produceError("there's no station with this id");
  } catch(PDOException $e){
    produceError($e->getMessage());
  }



?>
