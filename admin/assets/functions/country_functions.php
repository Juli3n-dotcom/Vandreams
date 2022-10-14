<?php
// récupération des pays
      function getCountry(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM country
        ');
       $country = $req->fetchAll(PDO::FETCH_ASSOC);
      return $country;
      }

// récupération des régions
function getRegion(PDO $pdo):array
{
  $req=$pdo->query(
    'SELECT *
    FROM region
  ');
 $region = $req->fetchAll(PDO::FETCH_ASSOC);
return $region;
}