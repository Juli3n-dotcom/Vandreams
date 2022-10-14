<?php
require_once __DIR__ . '/../config/bootstrap.php';

$favori = $_POST['idSupr'];
$annonce = $_POST['idannonce'];
$user = $_POST['iduser'];


$req = $pdo->exec("DELETE FROM favoris WHERE id_favori = '$favori'AND annonce_id ='$annonce'");

ajouterFlash('success','Annonce retirée de vos favoris');

$resultat = '';
$resultat .= '<form action="" method="POST">';
    $resultat .= '<input type="hidden" name="iduser" id="iduser" value="'.$user.'">';
    $resultat .= '<input type="hidden" name="idannonce" id="idannonce" value="'.$annonce.'">';
    $resultat .= '<button type="submit" class="favoris_fiche" id="addFavori" name="addFavori"><i class="far fa-heart"></i></button>';
$resultat .= '</form>';

$tableau['resultat'] = $resultat;

echo json_encode($tableau);