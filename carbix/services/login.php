<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);

require_once('lib/common_service.php');
require_once('lib/session_start.php');

if ( ! isset($_SESSION['ident'])) {
  $args = new RequestParameters();
  $args->defineNonEmptyString('pseudo');
  $args->defineNonEmptyString('password');

  if (! $args->isValid()){
   produceError(implode(' ',$args->getErrorMessages()));
   return;
  }

  else{
    try{
      $data = new DataLayer();
      $_SESSION['ident'] = $data->authentifier($args->pseudo,$args->password);
      if(!$_SESSION['ident']) produceError("Le mot de passe ou l'utilisateur est incorrect");
      else{
        produceResult(['pseudo'=>$_SESSION['ident']]);
      }
    } catch(PDOException $e){
      produceError($e->getMessage());
    }
  }

} else {
   produceError("déjà authentifié");
   return;
}

?>
