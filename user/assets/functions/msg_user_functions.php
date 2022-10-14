<?php
function getConversNonLu(PDO $pdo): array
{
   $req=$pdo->query("SELECT *
                    FROM conversation
                    WHERE destinataire ='$user'
                    ORDER BY date_enregistrement DESC");
    $newconver = $req->fectAll(PDO::FETCH_ASSOC);
    return $newconver;
}