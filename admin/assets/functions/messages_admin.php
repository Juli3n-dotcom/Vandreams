<?php
// récupération des msg non lu
      function getMessagesNonLu(PDO $pdo):array
      {
        $req=$pdo->query(
          'SELECT *
          FROM message_admin
          WHERE est_lu = 0
        ');
       $newmsg = $req->fetchAll(PDO::FETCH_ASSOC);
      return $newmsg;
      }

  // récupération de tous les messages
  function getOldMsg(PDO $pdo):array
  {
    $req=$pdo->query(
      'SELECT *
      FROM message_admin
      WHERE est_lu = 1
    ');
   $oldmsg = $req->fetchAll(PDO::FETCH_ASSOC);
  return $oldmsg;
  }

  // récupération des msg envoyé
  function getMsgSend(PDO $pdo):array
  {
    $req=$pdo->query(
      'SELECT *
      FROM reponse_admin
      ORDER BY date_enregistrement DESC
    ');
   $msgSend = $req->fetchAll(PDO::FETCH_ASSOC);
  return $msgSend;
  }