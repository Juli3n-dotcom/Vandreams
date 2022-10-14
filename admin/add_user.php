<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

if (isset($_POST['register'])){

    if(getMembreBy($pdo, 'email', $_POST['email'])!==null) {
        ajouterFlash('danger','Email déja utilisé !');

    }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
   ajouterFlash('danger','Email non valide.');

    }elseif (!preg_match('~^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$~',$_POST['password'])) {
        ajouterFlash('danger','Votre mot de passe doit contenir :minimum 8 caractéres, 1 maj, 1min, 1chiffre  et 1 caractére spécial.');
      
     
    }elseif ($_POST['password'] !== $_POST['confirm'] ){
        ajouterFlash('danger','Merci de confirmer votre mot de passe.');
        
    }elseif (empty($_POST['name'])) {
        ajouterFlash('danger','Nom manquant');
    
    }elseif (empty($_POST['first_name'])) {
        ajouterFlash('danger','Prénom manquant');
    
    }else{

        
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $ip = "null";
        $explode = explode(' ',$_POST['name']);
        $name = $explode[0].$explode[1].$explode[2].$explode[3]; 

        $req = $pdo->prepare(
            'INSERT INTO membre (email, name, password, civilite, nom, prenom, statut, date_enregistrement, confirmation, token, ip)
            VALUES (:email, :name, :password, :civilite, :nom,:prenom, :statut, :date, :confirmation, :token, :ip)'
        );

        $req->bindParam(':email',$_POST['email']);
        $req->bindParam(':name',$name);
        $req->bindParam(':password',$hash);
        $req->bindValue(':civilite',$_POST['civilite']);
        $req->bindParam(':nom',$_POST['name']);
        $req->bindParam(':prenom',$_POST['first_name']);
        $req->bindValue(':statut',$_POST['role']);
        $req->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
        $req->bindValue(':confirmation',isset($_POST['check']),PDO::PARAM_BOOL);
        $req->bindParam(':token',bin2hex(random_bytes(16)));
        $req->bindParam(':ip',$ip);
        $req->execute();

        unset($_POST);
        ajouterFlash('success','Membre ajouté!');
        session_write_close();
        header('location:user.php');
    }
}


$page_title ='Ajout de membres';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1><i class="fas fa-user-plus"></i> Ajouter un membre</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="container-fluid">

<form action="add_user.php" method="POST">
    <div class="form-group row">
        <label for="civilite" class="col-sm-2 col-form-label">Civilité : </label>
        <div class="col-sm-10">
            <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="civilite">
                <option value="">...</option>
                <option value="<?= FEMME ?>">Mme</option>
                <option value="<?= HOMME ?>">Mr</option>
            </select>
        </div>
    </div>
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label">Email : </label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="email" value="<?= $_POST['email'];?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label">Mot de passe :</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password">
    </div>
  </div>
  <div class="form-group row">
    <label for="confirm" class="col-sm-2 col-form-label">Confirmer mdp :</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="confirm">
    </div>
  </div>
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Nom : </label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" value="<?= $_POST['name'];?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="first_name" class="col-sm-2 col-form-label">Prénom : </label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="first_name" value="<?= $_POST['first_name'];?>">
    </div>
  </div>
  <div class="form-group row">
        <label for="role" class="col-sm-2 col-form-label">Role : </label>
        <div class="col-sm-10">
            <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="role">
                <option value="">...</option>
                <option value="<?= ROLE_ADMIN ?>">Admin</option>
                <option value="<?= ROLE_USER ?>">User</option>
            </select>
        </div>
    </div>
  <div class="form-group row">
    <div class="col-sm-2">Confirmation : </div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="" type="checkbox" name="check">
        <label class="form-check-label" for="check">
          Confirmer inscription
        </label>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-10">
    <button type="submit" class="btn btn-primary" name="register">Valider</button>
    </div>
  </div>
</form>



</div>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>