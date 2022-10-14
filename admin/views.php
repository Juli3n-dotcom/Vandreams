<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';

$annee = (int)date('Y');
$annee_selection= empty($_GET['annee']) ? null : (int)$_GET['annee'];
$mois_selection= empty($_GET['mois']) ? null : $_GET['mois'];
if($annee_selection && $mois_selection){
    $total = nombre_vues_mois($annee_selection, $mois_selection);
    $detail = nombre_vues_detail_mois($annee_selection, $mois_selection);
}else{
    $total = nombre_vues();
}

$mois = [
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Aout',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre'
];

$page_title ='Stats Visites';
include __DIR__.'/assets/includes/header_admin.php';
?>

<div class="title_page">
    <h1><i class="fas fa-eye"></i> Stats Visites</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="row">
    <div class="col-md-4">
        <div class="list-group">
        <?php for($i = 0; $i < 5; $i++):?>
            <a class="list-group-item .views_link <?= $annee - $i === $annee_selection ? 'active':'' ?>" href="views.php?annee=<?= $annee - $i ?>"><?= $annee - $i ?></a>
            <?php if ($annee - $i === $annee_selection):?>
                <div class="list-group">
                    <?php foreach($mois as $j => $nom):?>
                    <a href="views.php?annee=<?= $annee_selection?>&mois=<?= $j ?>" class="list-group-item .views_link <?= $j === $mois_selection ? 'active':''?>">
                        <?= $nom ?>
                    </a>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
        <?php endfor ?>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <strong style="font-size:3em"><?= $total ?></strong>
                <br>
                visite<?= $total > 1 ? 's' : ''?> total
            </div>
        </div>

        <div class="calendar">
            <p id="monthName"></p>
            <p id="dayName"></p>
            <p id="dayNumber"></p>
            <p id="year"></p>
        </div>

        <?php if (isset($detail)):?>
        <h2>Detail des visites pour le mois</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jour</th>
                        
                        <th>Nombre de visites</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($detail as $ligne):?>
                <tr>
                    <td><?= $ligne['jour']?></td>
                    
                    <td><?= $ligne['visites']?> visite<?=$ligne['visites']>1 ?'s':''?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php endif;?>
    </div>
</div>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>