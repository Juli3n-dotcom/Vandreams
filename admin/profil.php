<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);

if(($Membre === null)){
  ajouterFlash('danger','Veuillez vous connecter');
  header('location:../login.php');
}

//modification membre
if(isset($_POST['update'])){

    if (!preg_match('~^[a-zA-Z-]+$~',$_POST['name'])) {
     ajouterFlash('danger','Nom manquant');
 
   }elseif (!preg_match('~^[a-zA-Z-]+$~',$_POST['first_name'])) {
     ajouterFlash('danger','prénom manquant');
 
   }elseif (!preg_match('~^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$~',$_POST['password'])) {
    ajouterFlash('danger','mot de passe doit contenir :minimum 8 caractéres, 1 maj, 1min, 1chiffre  et 1 caractére spécial.');

}elseif ($_POST['password'] !== $_POST['confirm'] ){
    ajouterFlash('danger','Merci de confirmer votre mot de passe.');
  
 
   }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    ajouterFlash('danger','email non valide.');
 
    
   }else{
 
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'].$_POST['first_name'];
 
     $req =$pdo ->prepare(
         'UPDATE membre SET
         nom = :nom,
         prenom = :prenom,
         password= :password,
         email = :email,
        name = :name
         WHERE id_membre = :id_membre'
     );
         $req->bindParam(':nom',$_POST['name']);
         $req->bindParam(':prenom',$_POST['first_name']);
         $req->bindParam(':password',$hash);
         $req->bindParam(':email',$_POST['email']);
         $req->bindParam(':name',$name);
         $req->bindParam(':id_membre',$_GET['id'],PDO::PARAM_INT);
         $req->execute();
 
       ajouterFlash('success','Profil modifié !');
 }
 }
 

$page_title ='Mes informations';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1>Bonjour <?= getMembre()['prenom']?> !</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 left">
        <div class='mb-3 mx-auto d-block'>
            <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-thumbnail" alt="avatar">
        </div>
        
        <div>
            <h5 class='mb-5'>Mes Informations : </h5>  
            <div class="info_admin">
                <p><strong>Nom : </strong><?= $Membre['nom']?></p> 
                
                <p><strong>Prenom : </strong><?= $Membre['prenom']?></p> 

                <p><strong>Email : </strong><?= $Membre['email']?></p> 

                <p><strong>Role : </strong><?= $Membre['statut'] == 0 ? 'User' :'Admin' ;?></p>  
            </div> 
            <div>
            <a href="profil.php?id=<?=$Membre['id_membre'];?>" class="btn btn-info" data-toggle="modal" data-target="#<?= $Membre['name'];?>"> <i class="fas fa-edit"></i> Update </a>

             <!-- Modal -->

             <div class="modal fade" id="<?= $Membre['name'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" >Modification Profil </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="profil.php?id=<?=$Membre['id_membre'];?>" method="post" class="form-inline">
                                            <div class="container">
                                                <div class="row">

                                                <div class="form-group col-12 mb-3">
                                                    <label for="email" class=" ml-2 mr-2 col-form-label">Email : </label>
                                                    <input type="email" class="form-control" name="email" value="<?= $Membre['email'];?>">
                                                </div>
                                                <div class="form-group row col-12 mb-3">
                                                    <label for="password" class=" ml-2 mr-2 col-form-label">mdp : </label>
                                                    <input type="password" class="form-control" name="password" value="<?= $Membre['password'];?>">
                                                </div>
                                                <div class="form-group row col-12 mb-3">
                                                    <label for="confirm" class=" ml-2 mr-2 col-form-label">Confirmation : </label>
                                                    <input type="password" class="form-control" name="confirm" value="<?= $Membre['password'];?>">
                                                </div>
                                                <div class="form-group col-12 mb-3">
                                                    <label for="name" class=" ml-2 mr-2 col-form-label">Nom : </label>
                                                    <input type="text" class="form-control" name="name" value="<?= $Membre['nom'];?>">
                                                </div>
                                                <div class="form-group col-12 mb-3">
                                                    <label for="first_name" class=" ml-2 mr-2 col-form-label">Prenom : </label>
                                                    <input type="text" class="form-control" name="first_name" value="<?= $Membre['prenom'];?>">
                                                </div>
                                    
                                                
                                           
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-primary" name="update" value="Valider" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
            </div>
        </div>
       
        <div class="col-md-9 right">
            
        </div>
        
        
    </div>
</div>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>