<?php
// récupération des catégories
      function getCategory(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM category
        ');
       $cat = $req->fetchAll(PDO::FETCH_ASSOC);
      return $cat;
      }

// récupération des sous catégories
function getSubCategory(PDO $pdo):array
{
  $req=$pdo->query(
    'SELECT *
    FROM sub_category
  ');
 $subcat = $req->fetchAll(PDO::FETCH_ASSOC);
return $subcat;
}

