<?php 
// récupération des membres
function getUser(PDO $pdo):array
{
  $req=$pdo->query(
    'SELECT *
    FROM membre
  ');
 $Membre = $req->fetchAll(PDO::FETCH_ASSOC);
return $Membre;
}

// Récupération annonce par User
function getDataUser(PDO $pdo, INT $id_membre)
    {      
        $req = $pdo->prepare(
          'SELECT  *
          FROM membre
          WHERE id_membre = :id_membre'        
        );     
      $req->bindParam(':id_membre', $id_membre, PDO::PARAM_INT);
      $req->execute() ;
      
      return $req->fetchALL(PDO::FETCH_ASSOC);
    }