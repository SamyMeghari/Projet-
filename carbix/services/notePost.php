<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineEnum("avis",['like','nolike']);
$args->defineNonEmptyString("id");

if (!$args->isValid()){
  produceError(implode(' ',$args->getErrorMessages()));
  return;
}

if ($args->avis!="like"&&$args->avis!="nolike"){
  produceError("cette note n'existe pas !");
  return;
}

try{
  $data = new DataLayer();
  $post = $data->notePost($_SESSION['ident'],$args->id,($args->avis=="like"));
  if ($post) produceResult($post);
  else produceError("Vous ne pouvez pas noter votre propre post !");
} catch (PDOException $e){
  produceError($e->getMessage());
}

?>
