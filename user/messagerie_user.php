<?php
require_once __DIR__ . '/../assets/config/bootstrap.php';
require __DIR__ . '/assets/functions/msg_user_functions.php';

$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);


// suppresion de conversation
if(isset($_POST['deleteConv'])){
    
    $req = $pdo->prepare(
        'DELETE FROM conversation
        WHERE :id = id_conversation'
    );
    $req->bindParam(':id', $_POST['idSupConvers'], PDO::PARAM_INT);
    $req->execute();

    ajouterFlash('success','Message supprimée !');
}

//traitement de la reponse
if(isset($_POST['sendmsg'])){

    if(empty($_POST['answer'])){

        ajouterFlash('danger','Votre message est vide.');

    }else{

        $message = htmlspecialchars($_POST['answer']);
        $conversation = $_POST['conversation_answer'];
        $destinataire = $_POST['destinataire_answer'];
        $date = (new DateTime())->format('Y-m-d H:i:s');

        $req = $pdo->prepare(
            'INSERT INTO message (expediteur, destinataire, conversation_id, message, date_enregistrement)
            VALUE (:expediteur, :destinataire, :conversation_id, :message, :date)'
        );
        $req -> bindParam(':expediteur', getMembre()['id_membre'], PDO::PARAM_INT);
        $req -> bindParam(':destinataire',$destinataire);
        $req -> bindParam(':conversation_id', $conversation);
        $req -> bindParam(':message', $message);
        $req -> bindValue(':date', $date);
        $req -> execute();

        //MAJ conversation
        $req_update = $pdo->prepare(
            'UPDATE conversation
             SET est_lu_expediteur = :lu_expediteur, 
             est_lu_destinataire = :lu_destinataire,
             expediteur = :expediteur, 
             destinataire = :destinataire,
             date_enregistrement = :date
             WHERE id_conversation = :id_conversation'
        );
        $req_update->bindValue(':lu_expediteur', 0);
        $req_update->bindValue(':lu_destinataire', 1);
        $req_update->bindValue(':expediteur',  getMembre()['id_membre'], PDO::PARAM_INT);
        $req_update->bindValue(':destinataire', $destinataire);
        $req_update->bindValue(':date', $date);
        $req_update->bindParam(':id_conversation',$conversation);
        $req_update->execute();

        $email = getMembre()['email'];
        $prenom = getMembre()['prenom'];
        $msg = $message;
        $id_destinataire = $_POST['destinataire_answer'];
        $data_membre = $pdo->query("SELECT * FROM membre WHERE id_membre = '$id_destinataire'");
        $membre = $data_membre->fetch(PDO::FETCH_ASSOC);
        
        $header="MIME-Version: 1.0\r\n";
        $header.='From:"vandreams.fr"<postmaster@vandreams.fr>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message = '
                <html>
                <head>
                  <title>Nouveau Message de '.$membre['prenom'].' - Van Dreams.fr</title>
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
                            <div align="center">Vous avez reçu un nouveau message de '.$membre['prenom'].'</div>
                            <br><br>
                            <div align="center">'.$msg.'</div>
                            <br><br>
                            <div align="center"><a href="https://www.vandreams.fr/login"
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
                            > ICI </a>Voir le message</div>
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
        mail($email, "Nouveau message de ".$membre['prenom']." - vandreams.fr", $message, $header);


        ajouterFlash('success','Message envoyé');
    }
}



$page_title ='Messagerie';
include __DIR__.'/assets/includes/header_user.php';
?>

<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="messagerie">
    <h1>Vous avez <?= $NewMessage > 0 ? $NewMessage : '0';?> nouveau<?= $NewMessage > 1 ? 'x' : '';?> message<?= $NewMessage > 1 ? 's' : '';?></h1>
    <div class="container">
        <div class="row">
        
            <div class="col-sm-4 col-md-12 reception">
                <?php 
                $user = $Membre['id_membre'];
                $allNewconver = $pdo->query("SELECT *
                             FROM conversation
                            WHERE destinataire ='$user' OR expediteur ='$user'
                            ORDER BY date_enregistrement DESC");
                ?>

            <?php while($newconver = $allNewconver ->fetch()):?>
                <?php if($newconver['destinataire'] == $Membre['id_membre']) :?>
                <?php 
                    $id_membre = $newconver['expediteur'];
                    $data_membre = $pdo->query("SELECT * FROM membre WHERE id_membre = '$id_membre'");
                    $expediteur = $data_membre->fetch(PDO::FETCH_ASSOC);
                ?>
                <div type="button" class="convers <?= $newconver['est_lu_destinataire'] == 1 ? 'lu' :'' ;?>" data-toggle="modal" data-target="#M<?=$newconver['id_conversation']?>">
                    <div class="convers_head">
                        <h5 class="convers_title_lu">
                        <?= $newconver['est_lu_destinataire'] == 1 ? '<i class="fas fa-envelope"></i>' :'' ;?> DE : <?= $expediteur['prenom']?>
                        </h5>
                    </div>
                        <div class="convers_body">
                            <?= $newconver['subject']?>
                            <!-- <form method="post">
                                <input type="hidden" name="idSupConvers" value="<?= $newconver['id_conversation']?>">
                                <button type="subit" name="deleteConv" class="deleteconv"><i class="fas fa-trash"></i></button>
                            </form> -->
                        </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="M<?=$newconver['id_conversation']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Conversation avec <?= $destinataire['prenom']?>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <?php
                                    $convers = $newconver['id_conversation'];
                                    $MsgOfConver = $pdo->query("SELECT * 
                                                                FROM message
                                                                WHERE conversation_id = '$convers'
                                                                ORDER BY date_enregistrement ASC");
                                    $msg = $MsgOfConver->fetchAll();
                                ?>

                                <?php foreach($msg as $key=> $msg):?>
                                    <div class="msg <?= $msg['expediteur'] === $Membre['id_membre'] ? 'msg_right' :'' ?>">
                                        <?= $msg['message']?>
                                    </div>
                                <?php endforeach;?>
                            </div>

                            <div class="msg_form">
                                <form method="post">
                                    <textarea type="text" name="answer" class="input_conversation" placeholder="Repondre"></textarea>
                                    <input type="hidden" name="destinataire_answer" value="<?= $msg['expediteur']?>">
                                    <input type="hidden" name="conversation_answer" value="<?= $convers?>">
                                    <input type="submit" name="sendmsg" class="btn_send_msg" value="Envoyer">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php elseif($newconver['expediteur'] == $Membre['id_membre']):?>

                    <?php 
                    $id_membre = $newconver['destinataire'];
                    $data_membre = $pdo->query("SELECT * FROM membre WHERE id_membre = '$id_membre'");
                    $destinataire = $data_membre->fetch(PDO::FETCH_ASSOC);
                    ?>
                <div type="button" class="convers" data-toggle="modal" data-target="#M<?=$newconver['id_conversation']?>">
                    <div class="convers_head">
                        <h5 class="convers_title">
                        <?= $newconver['est_lu_expediteur'] == 0 ? '<i class="fas fa-reply"></i>' :'' ;?>A : <?= $destinataire['prenom']?>
                        </h5>
                    </div>
                        <div class="convers_body">
                            <?= $newconver['subject']?>
                            <!-- <form method="post">
                                <input type="hidden" name="idSupConvers" value="<?= $newconver['id_conversation']?>">
                                <button type="subit" name="deleteConv" class="deleteconv"><i class="fas fa-trash"></i></button>
                            </form> -->
                        </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="M<?=$newconver['id_conversation']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Conversation avec <?= $destinataire['prenom']?>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <?php
                                    $convers = $newconver['id_conversation'];
                                    $MsgOfConver = $pdo->query("SELECT * 
                                                                FROM message
                                                                WHERE conversation_id = '$convers'
                                                                ORDER BY date_enregistrement ASC");
                                    $msg = $MsgOfConver->fetchAll();
                                ?>

                                <?php foreach($msg as $key=> $msg):?>
                                    <div class="msg <?= $msg['expediteur'] === $Membre['id_membre'] ? 'msg_right' :'' ?>">
                                        <?= $msg['message']?>
                                    </div>
                                <?php endforeach;?>
                            </div>

                            <div class="msg_form">
                                <form method="post">
                                    <textarea type="text" name="answer" class="input_conversation" placeholder="Repondre"></textarea>
                                    <input type="hidden" name="destinataire_answer" value="<?= $msg['destinataire']?>">
                                    <input type="hidden" name="conversation_answer" value="<?= $convers?>">
                                    <input type="submit" name="sendmsg" class="btn_send_msg" value="Envoyer">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                   
                <?php endif;?>
            <?php endwhile;?>
            

            

        </div>
    </div>
</div>

<?php
include __DIR__.'/assets/includes/footer_user.php';
?>