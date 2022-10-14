<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/messages_admin.php';


if(isset($_POST['envoyer'])){

   if (empty($_POST['message'])) {
   ajouterFlash('danger','il manque votre message.');

   }else{

    $name = 'nMsg'.bin2hex(random_bytes(6));

       $req = $pdo->prepare(
           'INSERT INTO reponse_admin (name, email, subject, message, message_id, date_enregistrement)
           VALUES (:name, :email, :subject, :message, :message_id, :date);'
       );
       $req->bindParam(':name',$name);
       $req->bindParam(':email', $_POST['email']);
       $req->bindParam(':subject', $_POST['subject']);
       $req->bindParam(':message', $_POST['message']);
       $req->bindParam(':message_id',$_POST['id_message']);
       $req->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
       $req->execute();

    }
   
    //Envoi Email:
    $destinataire = $_POST['email'];
    $header = $_POST['subject']."\n";
    $message = nl2br($_POST['message']);
    mail( $destinataire, $header, $message);
    
    $req2 = $pdo->prepare(
    'UPDATE message_admin SET
    est_lu = 1
    WHERE id_message ='.$_POST['id_message']
   );
   $req2->execute();

   unset($_POST);
   ajouterFlash('success','Message envoyé');
     
}

$page_title ='Nouveaux Messages';
include __DIR__.'/assets/includes/header_admin.php';
?>

<div class="title_page">
    <h1><i class="fas fa-envelope"></i> Nouveaux messages</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<?php foreach(getMessagesNonLu($pdo) as $newmsg) : ?>

<div class="card mb-4">
  <h5 class="card-header">Message de : <?= $newmsg['email']?> </h5>
    <div class="card-body">
        <h5 class="card-title "><?= $newmsg['subject']?></h5>
        <p class="card-text mb-4"><?= $newmsg['message']?></p>
        <a href="new_msg.php?id=<?=$newmsg['id_message'];?>" class="btn btn-info" data-toggle="modal" data-target="#<?= $newmsg['name'];?>">  Répondre </a>
    </div>
    <div class="card-footer text-muted">
    <?= $newmsg['date_enregistrement']?>
  </div>
</div>

<!-- Modal -->

<div class="modal fade" id="<?= $newmsg['name'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Répondre à  | <?= $newmsg['email']?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="post">
                                        <div class="form-group">
                                                <label for="subject">Le sujet : </label>
                                                <input class="form-control mb-3"  name="subject" rows="4"></input>
                                                <input type="hidden" name="id_message" value="<?=$newmsg['id_message']?>">
                                                <input type="hidden" name="email" value="<?=$newmsg['email']?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="message">Votre réponse : </label>
                                                <textarea class="form-control"  name="message" rows="4"></textarea>
                                            </div>
                                         </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-primary" name="envoyer" value="Envoyer" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>

<?php endforeach; ?>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>