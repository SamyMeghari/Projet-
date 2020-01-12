<?php
//Auteurs : Happe Josué et Boumahdi Samy
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');


$args = new RequestParameters();
$args->defineNonEmptyString('pseudo');
$args->defineEnum('size',['large','small'],['default'=>'small']);

if (! $args->isValid()){
  produceError('argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
  return;
}

try{
  $data = new DataLayer();
  if ($args->size==="large"){
    $descFile = $data->getBigAvatar($args->pseudo);
    $size = 256; //pour redimensionner l'image par défaut si il n'ya pas d'image dans la base de données
  }
  else if {
    ($args->size==="small") $descFile = $data->getSmallAvatar($args->pseudo);
    $size=48;
  }
  if ($descFile){
    if (is_null($descFile['data'])){ //on redimensionne l'image par défaut en un carré de bonnes dimensions
      $tmp = fopen('../images/avatar_def.png','r');
      $tmpimage = $createImageFromStream($tmp);
      fclose($tmp);
      $largeur = imagesx($tmpimage);
      $hauteur = imagesy($image);
      if ($largeur<$hauteur) $valMin = $largeur;
      else $valMin = $hauteur;
      $imageDefaut = imagecreatetruecolor($size,$size);
      imagecopyresampled($imageReduite, $image, 0, 0, ($largeur-$valMin)/2, ($hauteur-$valMin)/2, $size, $size, $valMin, $valMin);
    }
    else $flux = descFile['data'];

    $flux = is_null($descFile['data']) ? fopen('../images/avatar_def.png','r') : $descFile['data'];
    $mimeType = is_null($descFile['data']) ? 'image/png' : $descFile['mimetype'];

    header("Content-type: $mimeType");
    fpassthru($flux);
    exit();
  }
  else
    produceError('Utilisateur inexistant');
}
catch (PDOException $e){
  produceError($e->getMessage());
}

?>
