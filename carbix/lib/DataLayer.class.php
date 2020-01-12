<?php
//Auteurs : Happe Josué et Boumahdi Samy
require_once("lib/db_parms.php");

Class DataLayer{
    private $connexion;
    public function __construct(){

            $this->connexion = new PDO(
                       DB_DSN, DB_USER, DB_PASSWORD,
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                       ]
                     );
    }

    /* récupère les informations du profil d'un utilisateur
     * $pseudo : le pseudo de l'utilisateur sont on veut les informations
     * renvoie la liste des informations de l'utilisateur donné
     */
    function findUtilisateur($pseudo){
      $sql = <<<EOD
      select pseudo,mail,ville,description,nbavis,total,nbposts,nblike,nbnolike
      from carbix_users
      where pseudo=:pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->execute();
      $res = $stmt ->fetch();
      if($res) return $res;
      else return false;
    }

    /* trouve les 10 meilleures stations
     * retourne la liste des 10 stations ayant la meilleure note globale
     */
    function findBestStations(){
      $sql = <<<EOD
      select *
      from stationsp2
      order by moyenne_globale desc
      limit 10;
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetchAll();
      return $res;
    }

    /* trouve une station à partir de son identifiant
     * $id : l'identifiant de la station cherchée
     * retourne la station désirée avec ses caractéristiques
     */
    function findStation($id){
      $sql = <<<EOD
      select *
      from stationsp2
      where id=:id
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      $res = $stmt->fetch();
      if($res) return $res;
      else return false;
    }

    /* trouvent les posts à propos d'une certaine station
     * $id : l'identifiant de la station dont on veut les posts
     * retourne la liste des posts de cette station
     */
    function findPosts($id){
      $sql = <<<EOD
      select *
      from carbix_posts
      where station=:id
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      $res = $stmt->fetchAll();
      return $res;
    }

    /* trouve les posts d'un certain utilisateur
     * $pseudo : le pseudo de l'utilisateur
     * retourne la liste des posts de cet utilisateur triée par date de création décroissante
     */
    function findMesPosts($pseudo){
      $sql = <<<EOD
      select * from carbix_posts
      where auteur=:pseudo
      order by date_creation desc
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->execute();
      $res = $stmt->fetchAll();
      return $res;
    }

    /* créé un post à propos d'une station
     * $auteur : le pseudo de l'auteur du post
     * $station : l'id de la station commentée
     * $titre : le titre du post
     * $contenu : le contenu du post
     * retourne l'identifiant du post créé
     */
    function createPost($auteur,$station,$titre,$contenu){
      //on écrit le post
      $date = date('Y-m-d H:i:s');
      $sql = <<<EOD
      insert into carbix_posts(auteur,station,titre,contenu,nblike,nbnolike,date_creation) values (:auteur,:station,:titre,:contenu,0,0,:date)
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":auteur",$auteur);
      $stmt->bindValue(":station",$station);
      $stmt->bindValue(":titre",$titre);
      $stmt->bindValue(":contenu",$contenu);
      $stmt->bindValue(":date",$date);
      $stmt->execute();
      // on incrémente le nombre de posts que l'utilisateur a fait
      $this->incrementNbPosts($auteur);
      //on va chercher l'id du post créé
      $sql = "select id from carbix_posts order by id desc";
      $stmt = $this->connexion->prepare($sql);
      $stmt->execute();
      $res = $stmt->fetch();
      return $res;
    }

    /* supprime un post
     * $auteur : l'auteur du post supprimé
     * $id : l'id du post que l'on veut supprimer
     */
    function deletePost($auteur,$id){
      //on regarde si un post existe à l'id donné
      $sql = "select * from carbix_posts where id=:id";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      $res = $stmt->fetch();
      //si $res vaut false, c'est que le post n'existe pas
      if($res){
        //on décrémente le nombre de posts que l'utilisateur a fait
        $this->decrementNbPosts($auteur);
        $sql = <<<EOD
        delete from carbix_posts
        where id = :id
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        return true;
      }
      else return false;
    }

   /* modifie le profil d'un utilisateur lorsque il publie un post (on modifie la valeur nbposts)
    * $pseudo : le pseudo de l'utilisateur dont on modifie le profil
    */
   function incrementNbPosts($pseudo){
     $sql = "update carbix_users set nbposts = nbposts + 1 where pseudo=:pseudo";
     $stmt = $this->connexion->prepare($sql);
     $stmt->bindValue(":pseudo",$pseudo);
     $stmt->execute();
   }

   /* modifie le profil d'un utilisateur lorsque il supprime un post (on modifie la valeur nbposts)
    * $pseudo : le pseudo de l'utilisateur dont on modifie le profil
    */
   function decrementNbPosts($pseudo){
     $sql = "update carbix_users set nbposts = nbposts -1 where pseudo=:pseudo";
     $stmt = $this->connexion->prepare($sql);
     $stmt->bindValue(":pseudo",$pseudo);
     $stmt->execute();
   }

    /* note un post en y rajoutant un like ou un nolike
     * $pseudo : la personne qui note le post (pour vérifier qu'il ne s'agit pas de l'auteur du post)
     * $id = l'identifiant du post noté
     * $like : vaut true si on met un like au post, false si on met un nolike
     * renvoie le post modifié
     */
    function notePost($pseudo,$id,$like){
      //on cherche l'auteur du post pour vérifier que ce n'est pas la personne qui note le post
      $sql = "select * from carbix_posts where id=:id";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      $res = $stmt->fetch();
      $auteur=$res['auteur'];
      if ($auteur!=$pseudo){
        //on modifie le profil de l'auteur en modifiant son nombre de like ou de nolikes
        $this->modifNbLikes($auteur,$like);
        if ($like) $sql = "update carbix_posts set nblike = nblike + 1 where id=:id";
        else $sql = "update carbix_posts set nbnolike = nbnolike + 1 where id=:id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        //on récupère le post modifié
        $sql = "select * from carbix_posts where id=:id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $res = $stmt->fetch();
        return $res;
      }
      else return false;
    }

    /* modifie le profil d'un utilisateur lorsque l'un de ses posts est noté
     * $pseudo : le pseudo de l'utilisateur dont on incrémente le nombre de likes ou de nolikes
     * $like : vaut true si on incrémente le nombre de like, vaut false si on incrémente le nombre de nolike
     */
    function modifNbLikes($pseudo,$like){
      if ($like) $sql="update carbix_users set nblike = nblike + 1 where pseudo=:pseudo";
      else $sql="update carbix_users set nbnolike = nbnolike + 1 where pseudo=:pseudo";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->execute();
    }

    /* note une station sur 4 critères entre 0 et 5
     * $pseudo : le pseudo de l'utilisateur qui note la station
     * $id : l'identifiant de la station notée
     * $global : la note globale de la station
     * $accueil : la note d'accueil de la station
     * $prix : le note sur le prix de la station
     * $service : la note sur la qualité des services de la station
     * retourne la station modifiée
     */
    function noteStation($pseudo,$id,$global,$accueil,$prix,$service){
      //modification du profil de la personne qui note (notamment des champs total et nbavis)
      $this->modifNbAvis($pseudo,$global);
      //rajout des notes, pour obtenir la note globale moyenne par exemple, il faut diciser noteglobale par nbnotes
      $sql = <<<EOD
      update stationsp2
       set nbnotes = nbnotes+1,
       noteglobale = noteglobale + :global,
       noteaccueil = noteaccueil + :accueil,
       noteprix = noteprix + :prix,
       noteservice = noteservice + :service
       where id=:id
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":global",$global);
      $stmt->bindValue(":accueil",$accueil);
      $stmt->bindValue(":prix",$prix);
      $stmt->bindValue(":service",$service);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      //on met à jour la colonne moyenne_globale
      $sql = "update stationsp2 set moyenne_globale = cast(cast(noteglobale as float)/cast(nbnotes as float) as decimal(10,2)) where id=:id";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      //on récupère la station modifiée
      return $this->findStation($id);
    }

    /* modifie le profil d'un utilisateur lorsqu'il note une station
     * $pseudo : le pseudo de l'utilisateur
     * $noteglobale : la note globale donnée que l'on ajoute à total (la somme des notes globales données par l'utilisateur)
     */
    function modifNbAvis($pseudo,$noteglobale){
      $sql = <<<EOD
      update carbix_users
        set nbavis = nbavis + 1,
        total = total + :noteglobale
        where pseudo=:pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":noteglobale",$noteglobale);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->execute();
    }

    /* met à jour certaines informations du profil d'un utilisateur (lorsqu'elles sont renseignées)
     * $pseudo : le pseudo de l'utilisateur dont le profil est modifié
     * $mail : le nouveau mail de l'utilisateur
     * $description : la nouvelle description de l'utilisateur
     * $ville : la ville dans laquelle habite l'utilisateur
     * $password le nouveau mot de passe de l'utilisateur
     */
    function updateProfil($pseudo,$mail,$description,$ville,$password){
      $empreinte = password_hash($password,CRYPT_BLOWFISH);
      $sql = <<<EOD
      update carbix_users
      set mail = case
            when :mail='' then mail
            else :mail
            end,
      description = case
            when :description='' then description
            else :description
            end,
      ville = case
            when :ville='' then ville
            else :ville
            end,
      password = case
            when :password='' then password
            else :empreinte
            end
      where pseudo=:pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":mail",$mail);
      $stmt->bindValue(":description",$description);
      $stmt->bindValue(":ville",$ville);
      $stmt->bindValue(":password",$password);
      $stmt->bindValue(":empreinte",$empreinte);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->execute();
      return $this->findUtilisateur($pseudo);
    }

    /* Enregistre un avatar pour l'utilisateur
     * $pseudo : le pseudo de l'utilisateur
     * $imageSpec : un tableau associatif contenant deux clés :
     *    'data' : flux ouvert en lecture sur les données à stocker
     *    'mimetype' : type MIME (chaîne)
     * résultat : booléen indiquant si l'opération s'est bien passée
     */
    function storeBigAvatar($pseudo,$imageSpec){
      $sql = <<<EOD
      update carbix_users
        set big_avatar=:image, mimetype=:type
        where pseudo=:pseudo
EOD;
      $stmt=$this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
      $stmt->bindValue(":type",$imageSpec['mimetype'],PDO::PARAM_STR);
      $stmt->bindValue(":image",$imageSpec['data'],PDO::PARAM_LOB);
      try {
        return $stmt->execute();
      }
      catch (PDOException $e){
        return false;
      }
    }

    /* Enregistre un avatar pour l'utilisateur
     * $pseudo : le pseudo de l'utilisateur
     * $imageSpec : un tableau associatif contenant deux clés :
     *    'data' : flux ouvert en lecture sur les données à stocker
     *    'mimetype' : type MIME (chaîne)
     * résultat : booléen indiquant si l'opération s'est bien passée
     */
    function storeSmallAvatar($pseudo,$imageSpec){
      $sql = <<<EOD
      update carbix_users
        set small_avatar=:image, mimetype=:type
        where pseudo=:pseudo
EOD;
      $stmt=$this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
      $stmt->bindValue(":type",$imageSpec['mimetype'],PDO::PARAM_STR);
      $stmt->bindValue(":image",$imageSpec['data'],PDO::PARAM_LOB);
      try {
        return $stmt->execute();
      }
      catch (PDOException $e){
        return false;
      }
    }

    /*
     * Récupère l'avatar d'un utilisateur en taille 256x256
     * $pseudo : pseudo de l'utilisateur
     * résultat :
     *   si l'utilisateur existe : table assoc
     *    'mimetype' : mimetype de l'image
     *    'data' : flux ouvert en lecture sur les données binaires de l'image
     *     si l'utilisateur n'a pas d'avatar, 'mimetype' et 'data' valent NULL
     *   si l'utilisateur n'existe pas : le résultat vaut NULL
     */
    function getBigAvatar($pseudo){
       $sql = <<<EOD
       select mimetype, big_avatar
       from carbix_users
       where pseudo=:pseudo
EOD;
       $stmt = $this->connexion->prepare($sql);
       $stmt->bindValue(':pseudo', $pseudo);
       $stmt->bindColumn('mimetype', $mimeType);
       $stmt->bindColumn('avatar', $flow, PDO::PARAM_LOB);
       $stmt->execute();
       $res = $stmt->fetch();
       if ($res)
          return ['mimetype'=>$mimeType,'data'=>$flow];
       else
          return false;
     }

     /*
      * Récupère l'avatar d'un utilisateur en taille 256x256
      * $pseudo : pseudo de l'utilisateur
      * résultat :
      *   si l'utilisateur existe : table assoc
      *    'mimetype' : mimetype de l'image
      *    'data' : flux ouvert en lecture sur les données binaires de l'image
      *     si l'utilisateur n'a pas d'avatar, 'mimetype' et 'data' valent NULL
      *   si l'utilisateur n'existe pas : le résultat vaut NULL
      */
     function getSmallAvatar($pseudo){
        $sql = <<<EOD
        select mimetype, small_avatar
        from carbix_users
        where pseudo=:pseudo
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindColumn('mimetype', $mimeType);
        $stmt->bindColumn('avatar', $flow, PDO::PARAM_LOB);
        $stmt->execute();
        $res = $stmt->fetch();
        if ($res)
           return ['mimetype'=>$mimeType,'data'=>$flow];
        else
           return false;
      }

    /* crée un nouvel utilisateur
     * $pseudo : le pseudo de l'utilisateur créé
     * $password : le mot de passe de l'utilisateur créé
     */
    function createUtilisateur($pseudo,$password){
      $empreinte = password_hash($password,CRYPT_BLOWFISH);
      $sql = "insert into carbix_users(pseudo,password,nbavis,total,nbposts,nblike,nbnolike) values (:pseudo,:password,0,0,0,0,0)";
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(":pseudo",$pseudo);
      $stmt->bindValue(":password",$empreinte);
      $stmt->execute();
      }

      /* connecte l'utilisateur à son compte
       * $pseudo : le pseudo de nécessaire pour que l'utilisateur se connecte
       * $password : le mot de passe nécessaire pour que l'utilisateur se connecte
       */
      function authentifier($pseudo,$password){
        $sql = <<<EOD
        select pseudo, password
        from carbix_users
        where pseudo = :pseudo
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->execute();
        $info = $stmt->fetch();
        if ($info && crypt($password, $info['password']) == $info['password'])
              return $info['pseudo'];
        else
          return NULL;
      }

}

?>
