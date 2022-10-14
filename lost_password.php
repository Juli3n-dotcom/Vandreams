<?php
require_once __DIR__ . '/assets/config/bootstrap.php';


if (isset($_GET['section'])) {
    $section = htmlspecialchars($_GET['section']);
} else {
    $section = "";
}

if (isset($_POST['submit'], $_POST['email'])) {
    if (!empty($_POST['email'])) {
        $recup_email = htmlspecialchars($_POST['email']);
        if (filter_var($recup_email, FILTER_VALIDATE_EMAIL)) {
            $mailexist = $pdo->prepare('SELECT id_membre,prenom FROM membre WHERE email = ?');
            $mailexist->execute(array($recup_email));
            $mailexist_count = $mailexist->rowCount();
            if ($mailexist_count == 1) {
                $prenom = $mailexist->fetch(PDO::FETCH_ASSOC);
                $prenom = $prenom['prenom'];

                $_SESSION['recup_email'] = $recup_email;
                $recup_code = '';
                for ($i = 0; $i < 8; $i++) {
                    $recup_code .= mt_rand(0, 9);
                }
                // $_SESSION['recup_code']= $recup_code;

                $mail_recup_exist = $pdo->prepare('SELECT id FROM recuperation WHERE email = ?');
                $mail_recup_exist->execute(array($recup_email));
                $mail_recup_exist = $mail_recup_exist->rowCount();

                if ($mail_recup_exist == 1) {

                    $recup_insert = $pdo->prepare('UPDATE recuperation SET code = ? WHERE email = ?');
                    $recup_insert->execute(array($recup_code, $recup_email));
                } else {

                    $req2 = $pdo->prepare(
                        'INSERT INTO recuperation (email, code)
                    VALUE (:email, :code)'
                    );
                    $req2->bindParam(':email', $recup_email);
                    $req2->bindValue(':code', $recup_code);
                    $req2->execute();
                }

                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"vandreams.fr"<admin@webupdesign.fr>' . "\n";
                $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';
                $message = '
                <html>
                <head>
                  <title>Récupération de mot de passe - Van Dreams.fr</title>
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
                            
                            <div align="center">Bonjour <b>' . $prenom . '</b>,</div>
                            <br><br>
                            <div align="center">Voici votre code de récupération:</b>,</div>
                            <br><br>
                            <div align="center" 
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
                            ><b>' . $recup_code . '</b></div>
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
                mail($recup_email, "Récupération de mot de passe - vandreams.fr", $message, $header);
                header("Location:https://beta.julien-quentier.fr/lost_password.php?section=code");
                ajouterFlash('success', 'Email envoyé');
            } else {
                ajouterFlash('danger', 'Cette adresse email n\'est pas enregistrée');
            }
        } else {
            ajouterFlash('danger', 'Email non valide.');
        }
    } else {
        ajouterFlash('danger', 'Merci de rentrer une adresse email');
    }
}

if (isset($_POST['submit_verif'], $_POST['verif_code'])) {
    if (!empty($_POST['verif_code'])) {
        $verif_code = htmlspecialchars($_POST['verif_code']);
        $verif_req = $pdo->prepare('SELECT id FROM recuperation WHERE email = ? AND code = ?');
        $verif_req->execute(array($_SESSION['recup_email'], $verif_code));
        $verif_req = $verif_req->rowCount();
        if ($verif_req == 1) {
            $up_req = $pdo->prepare('UPDATE recuperation set confirm = 1 WHERE email = ?');
            $up_req->execute(array($_SESSION['recup_email']));
            header("Location:https://beta.julien-quentier.fr/lost_password.php?section=resetmdp");
        } else {
            ajouterFlash('danger', 'Code Invalide');
        }
    } else {
        ajouterFlash('danger', 'Veuillez entre votre code de confirmation');
    }
}


if (isset($_POST['submit_mdp'])) {
    if (isset($_POST['password'], $_POST['confirm'])) {
        $verif_confirme = $pdo->prepare('SELECT confirm FROM recuperation WHERE email = ?');
        $verif_confirme->execute(array($_SESSION['recup_email']));
        $verif_confirme = $verif_confirme->fetch();
        $confirmation = $verif_confirme['confirm'];
        if ($confirmation == 1) {
            $mdp = htmlspecialchars($_POST['password']);
            $mdpc = htmlspecialchars($_POST['confirm']);
            if (!empty($mdp) and !empty($mdpc)) {
                if ($mdp == $mdpc) {
                    $password = password_hash($mdp, PASSWORD_DEFAULT);
                    $ins_mdp = $pdo->prepare('UPDATE membre SET password = ? WHERE email = ?');
                    $ins_mdp->execute(array($password, $_SESSION['recup_email']));
                    $del_req = $pdo->prepare('DELETE FROM recuperation WHERE email = ?');
                    $del_req->execute(array($_SESSION['recup_email']));
                    header('location:login');
                } else {
                    ajouterFlash('danger', 'Vos mots de passe de correspondent pas ');
                }
            } else {
                ajouterFlash('danger', 'Veuillez remplir tous les champs');
            }
        } else {
            ajouterFlash('danger', 'Veuillez valider votre email avec le code de vérification');
        }
    } else {
        ajouterFlash('danger', 'Veuillez remplir tous les champs');
    }
}



$page_title = 'Récupération de mot de passe';
include __DIR__ . '/assets/includes/header.php';
?>

<?php include __DIR__ . '/assets/includes/flash.php'; ?>

<div class="hero_lost">
    <div class="form-box_lost">
        <h3 class="title_part">Réinitialiser mon mot de passe</h3>
        <?php if ($section == 'code') : ?>

            <form method="POST" class="input-group" id="lost">
                <p class="msg_code">Un code de vérification vous a été envoyé sur : <?= $_SESSION['recup_email'] ?></p>
                <br><br>
                <input type="number" pattern="[0-9]*" class="input-field_lost" name="verif_code" placeholder="Code de vérification">
                <button type="submit" class="submit-btn_lost" name="submit_verif">Valider</button>
            </form>

        <?php elseif ($section == "resetmdp") : ?>

            <form method="POST" class="input-group" id="lost">
                <input type="password" name="password" class="input-field" placeholder="Mot de passe">
                <input type="password" name="confirm" class="input-field" placeholder="Confirmer MDP">
                <button type="submit" class="submit-btn_lost" name="submit_mdp">Valider</button>
            </form>

        <?php else : ?>

            <form method="POST" class="input-group" id="lost">
                <input type="email" class="input-field_lost" name="email" placeholder="Votre adresse email" value="<?= htmlspecialchars($_POST['email']) ?? '' ?>">
                <button type="submit" class="submit-btn_lost" name="submit">Réinitialiser</button>
            </form>

        <?php endif; ?>

    </div>
</div>
<?php
include __DIR__ . '/assets/includes/cookie.php';
include __DIR__ . '/assets/includes/footer.php';
?>