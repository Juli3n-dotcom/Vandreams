<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/messages_admin.php';


$page_title ='Nouveaux Messages';
include __DIR__.'/assets/includes/header_admin.php';
?>

<div class="title_page">
    <h1><i class="far fa-paper-plane"></i> Messages Envoyés</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<?php foreach(getMsgSend($pdo) as $msgSend) : ?>

<div class="card mb-4">
  <h5 class="card-header">Message a : <?= $msgSend['email']?> </h5>
    <div class="card-body">
        <h5 class="card-title "><?= $msgSend['subject']?></h5>
        <p class="card-text mb-4"><?= $msgSend['message']?></p>
    </div>
    <div class="card-footer text-muted">
   Envoyé le : <?= $msgSend['date_enregistrement']?>
  </div>
</div>



<?php endforeach; ?>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>