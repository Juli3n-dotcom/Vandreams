<?php
//récupération des annonces
function getAnnonces(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM annonces
          ORDER BY date_enregistrement DESC'
        );
       $Annonce = $req->fetchAll(PDO::FETCH_ASSOC);
      return $Annonce;
        }

//récupération des annonces signalée
function getAnnoncesSignalee(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM annonces
          WHERE est_signal = 1
          ORDER BY date_enregistrement DESC'
        );
       $AnnonceSignalee = $req->fetchAll(PDO::FETCH_ASSOC);
      return $AnnonceSignalee;
        }

// récupération de la derniére annonce
function getLastAnnonce(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM annonces
          ORDER BY date_enregistrement DESC
          LIMIT 1'
        );
       $LastAnnonce = $req->fetchAll(PDO::FETCH_ASSOC);
      return $LastAnnonce;
        }