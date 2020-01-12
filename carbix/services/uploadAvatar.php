<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require('lib/watchdog_service.php');

  $image = createImageFromFile($_FILES['image']["tmp_name"]);
  $largeur = imagesx($image);
  $hauteur = imagesy($image);
  //on va stocker les hotos de profil sous les deux formats possibles
  $imageReduite = imagecreatetruecolor(256,256);
  $imageTresReduite = imagecreatetruecolor(48,48);
  //on cherche la plus petite valeur pour garder le plus grand carré possible
  if ($largeur<$hauteur) $valMin = $largeur;
  else $valMin = $hauteur;
  //on copie un carré de côté valMin centré sur l'image d'origine puis redimensionné aux tailles 256x256 et 48x48
  imagecopyresampled($imageReduite, $image, 0, 0, ($largeur-$valMin)/2, ($hauteur-$valMin)/2, 256, 256, $valMin, $valMin);
  imagecopyresampled($imageTresReduite, $image, 0, 0, ($largeur-$valMin)/2, ($hauteur-$valMin)/2, 48, 48, $valMin, $valMin);
  try{
    $data = new DataLayer();
    $fluxTemp = fopen("php://temp", "r+");
    imagejpeg($imageReduite, $fluxTemp);
    rewind($fluxTemp);
    $res = $data->storeBigAvatar($_SESSION['ident'],['data'=>$fluxTmp,'mimetype'=>'image/jpeg']]);
    fclose();
    if (!$res) produceError("la photo de profil 256x256 n'a pas pu être stockée");
    else{
      $fluxTemp = fopen("php://temp", "r+");
      imagejpeg($imageTresReduite, $fluxTemp);
      rewind($fluxTemp);
      $res = $data->storeSmallAvatar($_SESSION['ident'],['data'=>$fluxTmp,'mimetype'=>'image/jpeg']]);
      fclose();
      if (!$res) produceError("la photo de profil 256x256 n'a pas pu être stockée");
      else produceResult(["name"=>$_FILES['image']['name'],"size"=>$_FILES['image']['size'],"type"=>$_FILES['image']['type'],"error"=>$_FILES['image']['error']]);
    }
  } catch (PDOException $e){
    produceError($e->getMessage());
  }



 ?>
