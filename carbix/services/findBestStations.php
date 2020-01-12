<?php
//Auteurs : Happe Josué et Boumahdi Samy
  set_include_path('..'.PATH_SEPARATOR);
  require_once('lib/common_service.php');

  try {
    $data = new DataLayer();
    $best = $data->findBestStations();
    produceResult($best);
  }catch(PDOException $e){
    produceError($e->getMessage());
  }

?>
