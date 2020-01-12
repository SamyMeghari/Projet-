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
  $posts = $data->findPosts($args->id);
  produceResult(['date'=>date('Y/d/m H:i:s'),'liste'=>$posts]);
} catch(PDOException $e){
  produceError($e->getMessage());
}
?>
