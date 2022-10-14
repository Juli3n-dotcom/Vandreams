<?php
// Récupération annonce par User
function getAnnoncesByUser(PDO $pdo, INT $id_membre)
    {      
        $req = $pdo->prepare(
          'SELECT  *
          FROM annonces
          WHERE membre_id = :id_membre
          ORDER BY date_enregistrement DESC'        
        );     
      $req->bindParam(':id_membre', $id_membre, PDO::PARAM_INT);
      $req->execute() ;
      
      return $req->fetchALL(PDO::FETCH_ASSOC);
    }

// Récupération annonce par User
function getfavorisByUser(PDO $pdo, INT $id_membre)
    {      
        $req = $pdo->prepare(
          'SELECT  *
          FROM favoris
          WHERE membre_id = :id_membre'        
        );     
      $req->bindParam(':id_membre', $id_membre, PDO::PARAM_INT);
      $req->execute() ;
      
      return $req->fetchALL(PDO::FETCH_ASSOC);
    }