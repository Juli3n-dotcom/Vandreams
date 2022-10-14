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

// récupération des emails pour la newsletter
function getEmail(PDO $pdo):array
{
  $req=$pdo->query(
    'SELECT *
    FROM liste_newsletter
  ');
 $email = $req->fetchAll(PDO::FETCH_ASSOC);
return $email;
}
