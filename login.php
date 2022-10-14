<?php
require_once __DIR__ . '/assets/config/bootstrap.php';
require_once __DIR__ . '/assets/functions/register.php';

// traitement login
if(isset($_POST['login'])){
   
    $req = $pdo->prepare(
      'SELECT * 
      FROM membre
      WHERE
       email = :email'
    );
    $req->bindParam(':email',htmlspecialchars($_POST['identifiant']));
    $req->execute();
    $membre = $req->fetch(PDO::FETCH_ASSOC);
  
    if (!$membre) {
      ajouterFlash('danger','Membre inconnu.');
  
      
    }elseif (!password_verify($_POST['password_login'], $membre['password'])){
        ajouterFlash('danger','Mot de passe erroné!');
  
    }else{

        if(isset($_POST['rememberme'])){
            setcookie('token',$membre['token'],time()+365*24*3600, null, null, false, true);
        }
  
      unset($membre['password']);
      $_SESSION['membre']=$membre;
      ajouterFlash('success','Bonjour '.getMembre()['prenom']);
      session_write_close();
      if(!empty($_COOKIE["post"])){
        setcookie('post','',time()-3600);
        header('Location: post');
        
      }elseif(!empty($_COOKIE["allpost"])){
        ajouterFlash('danger','merci de vous connecter pour liker cette annonce.');
        setcookie('allpost','',time()-3600);
        header('Location: touteslesannonces');

      }elseif(!empty($_COOKIE["favindex"])){
        ajouterFlash('danger','merci de vous connecter pour liker cette annonce.');
        setcookie('favindex','',time()-3600);
        header('Location: welcome');
        
      }elseif(!empty($_COOKIE["fiche"])){
        setcookie('fiche','',time()-3600);
        header('Location: annonce/'.$_COOKIE["fiche"]);
      }else{
          header('Location: welcome');
      }
    }
  }


  
  // inscription
  if (isset($_POST['register'])){

    if(getMembreBy($pdo, 'email', $_POST['email'])!==null) {
        ajouterFlash('danger','Email déja utilisé !');

    }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
   ajouterFlash('danger','Email non valide.');

    }elseif (!preg_match('~^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$~',$_POST['password'])) {
        ajouterFlash('danger','Votre mot de passe doit contenir :minimum 8 caractéres, 1 maj, 1min, 1chiffre  et 1 caractére spécial.');
      
     
    }elseif ($_POST['password'] !== $_POST['confirm'] ){
        ajouterFlash('danger','Merci de confirmer votre mot de passe.');
        
    }elseif (!preg_match('~^[a-zA-Z-]+$~',$_POST['name'])) {
        ajouterFlash('danger','Nom manquant');
    
    }elseif (!preg_match('~^[a-zA-Z-]+$~',$_POST['first_name'])) {
        ajouterFlash('danger','Prénom manquant');

    }elseif (empty($_POST['cgu'])){
          ajouterFlash('danger','Merci d\'accepter les CGU');
        
    }else{

        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $ip = getIp();
        $explode_name = explode(' ',$_POST['name']);
        $explode_fn = explode(' ',$_POST['first_name']);
        $name = 'vd'.$explode_fn[0].$explode_name[0].bin2hex(random_bytes(6));
        $token = bin2hex(random_bytes(16));

        $req = $pdo->prepare(
            'INSERT INTO membre (email, name, password, nom, prenom, statut, cgu, date_enregistrement, confirmation, token, ip)
            VALUES (:email, :name, :password, :nom,:prenom, :statut, :cgu, :date, :confirmation, :token, :ip)'
        );

        $req->bindParam(':email',$_POST['email']);
        $req->bindParam(':name',$name);
        $req->bindParam(':password',$hash);
        $req->bindParam(':nom',htmlspecialchars($_POST['name']));
        $req->bindParam(':prenom',htmlspecialchars($_POST['first_name']));
        $req->bindValue(':statut',0);
        $req->bindValue(':cgu',1);
        $req->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
        $req->bindValue(':confirmation',0);
        $req->bindParam(':token',$token);
        $req->bindParam(':ip',$ip);
        $req->execute();

        

        $email = $_POST['email'];
        $prenom = $_POST['first_name'];

        $header="MIME-Version: 1.0\r\n";
        $header.='From:"vandreams.fr"<postmaster@vandreams.fr>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message = '
                <html>
                <head>
                  <title>Confirmer Votre adresse email - Van Dreams.fr</title>
                  <meta charset="utf-8" />
                </head>
                <body>
                  <font color="#303030";>
                    <div align="center">
                      <table width="600px">
                        <tr>
                       <img src="https://www.vandreams.fr/assets/img/logo3.png" alt="logo" width="200" style="display: block;margin-left: auto;
                         margin-right: auto;">
                          <td style="background-color: #EEE;height: 600px; border-radius: 10%; font-size: 20px; text-align:center;>
                            <div align="center">Bonjour <b>'.$prenom.'</b>,</div>
                            <br><br>
                            <div align="center">Bienvenue Chez Van Dreams.</div>
                            <br><br>
                            <div align="center">Merci de confirmer votre adresse email.</div>
                            <br><br>
                            <div align="center"><a href="https://vandreams.fr/confirm.php?name='.urlencode($name).'&token='.$token.'"
                            style=" width: 30%;
                                padding: 10px 30px;
                                cursor: pointer;
                                display: block;
                                margin: auto;
                                color: #FFF;
                                background: linear-gradient(to right, #00bd71,#008656);
                                border: 0;
                                outline: none;
                                border-radius: 30px;
                                text-decoration: none;"
                            >Je confirme mon email</a></div>
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

        unset($_POST);    
        ajouterFlash('success','Bienvenue!');
        header('location:login');
        session_write_close();
    }
}
  
$page_title ='connexion';
include __DIR__.'/assets/includes/header.php';
?>

<?php include __DIR__.'/assets/includes/flash.php';?>

<div class="hero_login">
    <div class="form-box">
        <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn"  id="login_btn">Connexion</button>
            <button type="button" class="toggle-btn"  id="register_btn">Inscription</button>
        </div>
        <form action="login.php" method="POST" class="input-group" id="login">
        <div class="logo"></div>
            <input type="email" class="input-field" name="identifiant" placeholder="Votre adresse email" value="<?= htmlspecialchars($_POST['identifiant']) ?? '' ?>">
            <input type="password" class="input-field" name="password_login" placeholder="Votre mot de passe">
            <input type="checkbox" class="check-box" name="rememberme"><span>Se souvenir de moi</span>
            <button type="submit" class="submit-btn" name="login">Connexion</button>
            <a href="resetpassword">Mot de passe oublié </a>
        </form>
        <form action="login.php" method="POST" class="input-group" id="register">
            <input type="text" name="name" class="input-field" placeholder="Votre Nom" value="<?= htmlspecialchars($_POST['name']) ?? '' ?>">
            <input type="text" name="first_name" class="input-field" placeholder="Votre Prénom" value="<?= htmlspecialchars($_POST['first_name']) ?? '' ?>">
            <input type="email" name="email" class="input-field" placeholder="Email" value="<?= htmlspecialchars($_POST['email']) ?? '' ?>">
            <input type="password" name="password" class="input-field password" placeholder="Mot de passe">
            <div class="pophover">
              <h6>Mot de passe:</h6>
              <p>Votre mot de passe doit contenir un minimim de 8 caractéres, une majuscule, une minuscule et un symbole.</p>
            </div>
            <input type="password" name="confirm" class="input-field" placeholder="Confirmer votre mot de passe">
            <input type="checkbox" class="check-box" name="cgu"><span class="cgu">J'accepte <a href="cgu">les conditions générales d'utilisation</a></span>
            <button type="submit" class="submit-btn" name="register">Valider</button>
        </form>
    </div>
</div>

<script type="text/javascript" src="assets/js/login.js"></script>
<?php
include __DIR__.'/assets/includes/cookie.php';
include __DIR__.'/assets/includes/footer.php';
?>