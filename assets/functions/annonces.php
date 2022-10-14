<?php
//Récupération d'une annonce par son identifiant 
    function getAnnonceByID(PDO $pdo,$id) : ?array
    {
       
      //Vérification de la valeur de $id
      if(!ctype_digit($id)){
        return null;
      }

      $req = $pdo->prepare(
      'SELECT * 
      from annonces
      WHERE id_annonce = :id_annonce'
      );

      $req->bindParam(':id_annonce',$id, PDO::PARAM_INT);
      $req->execute();

      $post= $req->fetch(PDO::FETCH_ASSOC);
    return $post ?: null;  
    }



//récupération des annonces
function getAnnonces(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM annonces
          ORDER BY date_enregistrement DESC
          LIMIT 10'
        );
       $annonce = $req->fetchAll(PDO::FETCH_ASSOC);
      return $annonce;
        }

//récupération des annonces des vans aménagé (index)
function getAnnoncesSubCat(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM annonces
          WHERE subcat_id = 2
          ORDER BY date_enregistrement DESC'
        );
       $annonce = $req->fetchAll(PDO::FETCH_ASSOC);
      return $annonce;
        }

//récupération des annonce similaire
function getOtherAnnonce(PDO $pdo, $subcat_id, $id)
      {
        $req=$pdo->prepare(
          'SELECT *
          FROM annonces
          WHERE subcat_id = :subcat_id
          AND id_annonce != :id
          ORDER BY date_enregistrement DESC
          LIMIT 3'
        );
        $req->bindParam(':subcat_id', $subcat_id);
        $req->bindParam(':id', $id);
        $req->execute();
       $annonce = $req->fetchAll(PDO::FETCH_ASSOC);
      return $annonce;
        }
