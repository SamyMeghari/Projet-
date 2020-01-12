<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('pseudo');

if (!$args->isValid()){
  produceError(implode(' ',$args->getErrorMessages()));
  return;
}

try{
  $data = new DataLayer();
  $user = $data->findUtilisateur($args->pseudo);
  if ($user) produceResult($user);
  else produceError("this pseudo has not been found");
} catch(PDOException $e){
  produceError($e->getMessage());
}


?>
