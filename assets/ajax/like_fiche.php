<?php 
require_once __DIR__ . '/../config/bootstrap.php';

$annonce = $_POST['idannonce'];
$user = $_POST['iduser'];

$req = $pdo->exec(
    "INSERT INTO favoris(membre_id, annonce_id, est_favori)
    VALUES ('$user', '$annonce', 1)
    ");


ajouterFlash('success','Annonce sauvegardée');

$favori = $pdo->lastInsertId();

$resultat = '';
$resultat .= '<form method="POST">';
    $resultat .= ' <input type="hidden" name="idSupr" value="'.$favori.'">';
    $resultat .= '<input type="hidden" name="idannonce" id="idannonce" value="'.$annonce.'">';
    $resultat .= '<input type="hidden" name="iduser" id="iduser" value="'.$user.'">';
    $resultat .= ' <button type="submit" class="removefavori_fiche" id="removeFavori" name="removeFavori"><i class="fas fa-heart"></i></button>';
$resultat .= '</form>';

$tableau['resultat'] = $resultat;

echo json_encode($tableau);