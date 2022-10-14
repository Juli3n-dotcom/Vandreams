<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/annonces_functions.php';
$vues = nombre_vues();
$page_title ='Back-office';
include __DIR__.'/assets/includes/header_admin.php';
?>

<div class="title_page">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
</div>

<div class="calendar">
  <p id="monthName"></p>
  <p id="dayName"></p>
  <p id="dayNumber"></p>
  <p id="year"></p>
</div>


<div class="py-5"> <!-- Traffic -->
    <div class="container-fluid">
        <h4 class="title_container"><i class="fas fa-eye"></i> Trafic</h4>
      <div class="row hidden-md-up">
          
        <div class="col-md-3">
          <div class="card text-white text-center bg-info"> 
            <div class="card-header">Visiteurs en ligne</div>
                <div class="card-body">
                    <p class="card-text"><?php echo $user_nbr; ?></p>
                </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card text-white text-center bg-info"> 
            <div class="card-header">Nombre de visiteurs total</div>
                <div class="card-body">
                    <p class="card-text"><?php echo $vues; ?></p>
                </div>
          </div>
        </div>

        

      </div> <!-- end row -->
    </div> <!-- end container-->
  </div>

  <div class="py-5"> <!-- Membre -->
    <div class="container-fluid">
        <h4 class="title_container"><i class="fas fa-user"></i> Les Membres</h4>
      <div class="row hidden-md-up">
          
        <div class="col-md-3">
          <div class="card text-white text-center bg-primary"> 
            <div class="card-header">Dernier membre inscript</div>
                <div class="card-body">
                    <p class="card-text"><?php echo $user_nbr; ?></p>
                </div>
          </div>
        </div>
        <?php
        $counter =$pdo->query('SELECT COUNT(*) as nb FROM membre WHERE statut = 0');
        $data_membre = $counter->fetch();
        $totalMembre =$data_membre['nb'];
        ?>
        <div class="col-md-3">
          <div class="card text-white text-center bg-primary"> 
            <div class="card-header">Membres total</div>
                <div class="card-body">
                    <p class="card-text"><?= $totalMembre; ?></p>
                </div>
          </div>
        </div>

        </div> <!-- end row -->
    </div> <!-- end container-->
  </div>

  <div class="py-5"> <!-- Membre -->
    <div class="container-fluid">
        <h4 class="title_container"><i class="fas fa-archive"></i> Les Annonces</h4>
      <div class="row hidden-md-up">
      
          
        <div class="col-md-3">
          <div class="card text-white text-center bg-success"> 
            <div class="card-header">Dernière annonce</div>
            <?php foreach(getLastAnnonce($pdo) as $key => $Annonce) :?>
                <div class="card-body">
                    <p class="card-text"><?php  echo $Annonce['titre_annonce'] ?></p>
                </div>
            <?php endforeach;?>
          </div>
        </div>
        <?php
        $counter =$pdo->query('SELECT COUNT(*) as nb FROM annonces');
        $data_annonces = $counter->fetch();
        $totalAnnonces =$data_annonces['nb'];
        ?>
        <div class="col-md-3">
          <div class="card text-white text-center bg-success"> 
            <div class="card-header">Annonces totales</div>
                <div class="card-body">
                    <p class="card-text"><?= $totalAnnonces; ?></p>
                </div>
          </div>
        </div>

        <?php
        $annoncesSignale=$pdo->query('SELECT COUNT(*)AS nb FROM annonces WHERE est_signal = 1');
        $data = $annoncesSignale ->fetch();
        $signal = $data['nb'];
        ?>
        <div class="col-md-3">
          <div class="card text-white text-center bg-warning"> 
            <div class="card-header">Annonces signalées</div>
                <div class="card-body">
                    <p class="card-text"><?= $signal; ?></p>
                </div>
          </div>
        </div>


        </div> <!-- end row -->
    </div> <!-- end container-->
  </div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>