<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

if (!isset ($_SESSION['ident'])){
  $args = new RequestParameters("post");
  $args->defineNonEmptyString('pseudo');
  $args->defineNonEmptyString('password');

  if(!$args->isValid()){
    produceError(implode(' ',$args->getErrorMessages()));
    return;
  }

  try{
    $data = new DataLayer();
    $data->createUtilisateur($args->pseudo,$args->password);
    $_SESSION['ident']=$args->pseudo;
    produceResult($args->pseudo);
  } catch(PDOException $e){
    produceError("pseudo déjà pris, l'utilisateur n'a pas pu être créé");
  }
}
else produceError("l'utilisateur est déjà connecté")
?>
