<?php

require_once __DIR__ . '/assets/config/bootstrap.php';

if(isset($_GET['name'],$_GET['token'])AND !empty($_GET['name']) AND !empty($_GET['token'])){

    $name = htmlspecialchars(urldecode($_GET['name']));
    $token = htmlspecialchars($_GET['token']);
    $req = $pdo->prepare("SELECT * FROM membre WHERE name = ? AND token = ?");
    $req -> execute(array($name, $token));

    $userexist = $req->rowCount();

    if($userexist == 1){
        $user = $req ->fetch();
        if($user['confirmation'] == 0){
            $updateuser = $pdo->prepare('UPDATE membre SET confirmation = 1 WHERE name = ? AND token = ?');
            $updateuser->execute(array($name,$token));
            ajouterFlash("success","Votre compte est maintenant confirmé!");
            header('location:welcome');
        }else{
            ajouterFlash("success","Votre compte est déjà validé!");
            header('location:welcome');
        }
    }else{
        ajouterFlash("danger","l\'utilisateur n\'existe pas !");
        header('location:welcome');
    }
}

?>