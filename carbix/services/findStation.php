<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('id');

if (!$args->isValid()){
  produceError(implode(' ',$args->getErrorMessages()));
  return;
}

try{
  $data = new DataLayer();
  $station = $data->findStation($args->id);
  if ($station) produceResult($station);
  else produceError("there's no station with this id");
} catch(PDOException $e){
  produceError($e->getMessage());
}

?>
