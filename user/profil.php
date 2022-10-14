<?php
require_once __DIR__ . '/../assets/config/bootstrap.php';
require __DIR__ . '/assets/functions/membre_functions.php';

$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);


if(isset($_POST['updateEmail'])){
    if(getMembreBy($pdo, 'email', $_POST['newEmail'])!==null) {
        ajouterFlash('danger','Email déja utilisé !');

    }elseif (!filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {
   ajouterFlash('danger','Email non valide.');
}else{
    $newtoken = bin2hex(random_bytes(16));

    $req_update = $pdo->prepare(
        'UPDATE membre SET
        email = :email,
        confirmation = :confirmation
        WHERE id_membre = :id'
    );
    $req_update->bindValue(':email',$_POST['newEmail']);
    $req_update->bindValue(':confirmation',0);
    $req_update->bindParam(':id',$_POST['idmembre'],PDO::PARAM_INT);
    $req_update->execute();

    $email = $Membre['email'];
    $prenom = $Membre['prenom'];
    $name = $Membre['name'];
    $token = $Membre['token'];

        $header="MIME-Version: 1.0\r\n";
        $header.='From:"vandreams.fr"<postmaster@vandreams.fr>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message = '
                <html>
                <head>
                  <title>Confirmer Votre nouvelle adresse email - Van Dreams.fr</title>
                  <meta charset="utf-8" />
                </head>
                <body>
                  <font color="#303030";>
                    <div align="center">
                      <table width="600px">
                        <tr>
                          <td>
                            
                            <div align="center">Bonjour <b>'.$prenom.'</b>,</div>
                            <br><br>
                            <div align="center">Bienvenue Chez Van Dreams.</div>
                            <br><br>
                            <div align="center"><a href="https://beta.julien-quentier.fr/confirm.php?name='.urlencode($name).'&token='.$token.'">Merci de confirmer votre nouvelle adresse email</a></div>
                            <br><br>
                            <div align="center">A bientôt sur <a href="vandreams.fr">VanDreams.fr</a> !</div>
                            
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <font size="2">
                              Ceci est un email automatique, merci de ne pas y répondre
                            </font> 
                          </td>
                        </tr>
                      </table>
                    </div>
                  </font>
                </body>
                </html>
                ';
    
    mail($email, "Confirmer votre email - vandreams.fr", $message, $header);
    session_write_close();
    ajouterFlash('success','Modification effectuée'); 
    header('location:myaccount');   
}
}


//MAJ mot de passe
if(isset($_POST['updateMdp'])){
    if (!preg_match('~^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$~',$_POST['password'])) {
        ajouterFlash('danger','Votre mot de passe doit contenir :minimum 8 caractéres, 1 maj, 1min, 1chiffre  et 1 caractére spécial.');
      
     
    }elseif ($_POST['password'] !== $_POST['confirm'] ){
        ajouterFlash('danger','Merci de confirmer votre mot de passe.');
    }else{

        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $req_update = $pdo->prepare(
            'UPDATE membre SET
            password = :password
            WHERE id_membre = :id'
        );
        $req_update->bindValue(':password',$hash);
        $req_update->bindParam(':id',$_POST['idmembre'],PDO::PARAM_INT);
        $req_update->execute();

        ajouterFlash('success','Modification effectuée'); 
        header('location:myaccount');   
    }
}

// tratement suppression
if(isset($_POST['delete_membre'])){

    if(!isset($_POST['delete_check'])){
        ajouterFlash('danger','Merci de confirmer la suppression !');
    
      }else{

    $req =$pdo->prepare(
    'DELETE FROM membre
     WHERE :id= id_membre'
 );
 
    $req->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
    $req->execute();

    ajouterFlash('success','Votre compte a bien été supprimé !');
    header('location:../logout');
      }
}  

$page_title ='Mon Compte';
include __DIR__.'/assets/includes/header_user.php';
?>

<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="myaccount">
    <h1>Mes Informations</h1>

    <div class="container">
        <div class="row justify-content-center">
            <div class="img_profil_myaccount"></div>
            <?php foreach(getDataUser($pdo ,$Membre['id_membre']) as $user):?>
              
            <div class="infos_profil">
                <p> <span class="title_profil">Votre Nom : </span><?= $user['nom'];?></p>
                <p> <span class="title_profil">Votre Prénom : </span><?= $user['prenom'];?></p>
                <p> <span class="title_profil">Votre Email : </span><?= $user['email'];?>
                    <a href="myaccount/<?=$Membre['id_membre'];?>" class="" data-toggle="modal" data-target="#updateEmail">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <p> <span class="title_profil">Mot de passe: </span> 
                <a href="myaccount/<?=$Membre['id_membre'];?>" class="" data-toggle="modal" data-target="#updateMdp">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <button class="btn supProfil" data-toggle="modal" data-target="#deleteCompte">Supprimer mon compte</button>
            </div><!-- end infos_profil-->

            <!-- Modal Update email-->
<div class="modal fade" id="updateEmail" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Modification Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
            <input type="email" class="input-field" name="newEmail" placeholder="Votre nouvelle adresse email" >
        
      </div>
      <div class="modal-footer">
      <button type="button" class="btn annulebtn" data-dismiss="modal">Annuler</button>
        <input type="hidden" name="idmembre" value="<?= $Membre['id_membre']?>">
        <button type="submit" name="updateEmail" class="btn updateAnnonce">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Update Mdp-->
<div class="modal fade" id="updateMdp" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" ">Modification mot de passe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
            <input type="password" name="password" class="input-field" placeholder="Nouveau mot de passe">
            <input type="password" name="confirm" class="input-field" placeholder="Confirmer votre nouveau mot de passe">
        
      </div>
      <div class="modal-footer">
      <button type="button" class="btn annulebtn" data-dismiss="modal">Annuler</button>
        <input type="hidden" name="idmembre" value="<?= $Membre['id_membre']?>">
        <button type="submit" name="updateMdp" class="btn updateAnnonce">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>

 <!-- Modal suppression de compte-->
 <div class="modal fade" id="deleteCompte" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Suppresion de compte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form  method="post">
                                            
                <p class="mb-2">Etes vous sur de vouloir supprimer votre compte ?</p>
                                            
                <div class='confirm_delete' id="confirm_delete">
                    <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                    <input type="hidden" name="idSupr" value="<?=$Membre['id_membre'];?>">
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn annulebtn" data-dismiss="modal">Annuler</button>
                    <input type="submit" class="btn SupAnnonce" name="delete_membre" value="Supprimer Mon compte" >
                </div>
            </form>  
        </div>
    </div>
  </div>
</div>
            <?php endforeach; ?>
        </div> <!-- end row-->
    </div><!-- end container-->
    
</div> <!-- end myaccount-->


<?php
include __DIR__.'/assets/includes/footer_user.php';
?>