<?php
function getfavori(PDO $pdo, $id_membre, $id_annonce)
{
$req = $pdo->prepare(
    'SELECT *
    FROM favoris
    WHERE membre_id = :id_membre
    AND annonce_id = :id_annonce'
);
$req->bindParam(':id_membre', $id_membre);
$req->bindParam(':id_annonce', $id_annonce);
$req->execute();
$favoris = $req->fetch(PDO::FETCH_ASSOC);

if($favoris == null){
    return false;
}
    return $favoris['id_favori'];
}


 