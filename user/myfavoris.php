<?php
require_once __DIR__ . '/../assets/config/bootstrap.php';
require __DIR__ . '/assets/functions/annonces_by_user.php';

$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);

//sup favori
if(isset($_POST['removeFavori'])){
    $req = $pdo->prepare(
      'DELETE FROM favoris
      WHERE :id = id_favori'
    );
    $req->bindParam(':id',$_POST["idSupr"],PDO::PARAM_INT);
    $req->execute();
  
    ajouterFlash('success','Annonce retirée de vos favoris');
  }
  

$page_title ='Mes favoris';
include __DIR__.'/assets/includes/header_user.php';
?>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="myfavoris">
<?php 
    $user = $Membre['id_membre'];
    $count = $pdo->query("SELECT id_favori FROM favoris WHERE membre_id ='$user'");
    $count->execute();
    $count = $count->rowCount();
?>
    <h1>Mes favoris</h1>
<?php if($count > 0):?>
    <div class="container">
        <div class="row">
        <?php foreach(getfavorisByUser($pdo,$Membre['id_membre']) as $favori):?>
        
            <?php

                $annonce_id = $favori['annonce_id'];
                
                $annonce_data = $pdo->query("SELECT * FROM annonces WHERE id_annonce ='$annonce_id'");
                $annonce = $annonce_data->fetch(PDO::FETCH_ASSOC);

                    $id_membre = $annonce['membre_id'];
                    $id_photo = $annonce['photo_id'];
                    $id_country = $annonce['country_id'];
                    $id_region = $annonce['region_id'];
                    $id_category = $annonce['category_id'];
                    $id_subcat = $annonce['subcat_id'];

                    $data = $pdo->query("SELECT * FROM photo WHERE id_photo = '$id_photo'");
                    $photo = $data->fetch(PDO::FETCH_ASSOC);

                    $data_membre = $pdo->query("SELECT * FROM membre WHERE id_membre = '$id_membre'");
                    $membre = $data_membre->fetch(PDO::FETCH_ASSOC);

                    $data_country = $pdo->query("SELECT * FROM country WHERE id_country = '$id_country'");
                    $country = $data_country->fetch(PDO::FETCH_ASSOC);

                    $data_region = $pdo->query("SELECT * FROM region WHERE id_region = '$id_region'");
                    $region = $data_region->fetch(PDO::FETCH_ASSOC);

                    $data_category = $pdo->query("SELECT * FROM category WHERE id_category = '$id_category'");
                    $category = $data_category->fetch(PDO::FETCH_ASSOC);

                    $data_sub = $pdo->query("SELECT * FROM sub_category WHERE id_sub_cat = '$id_subcat'");
                    $subcat = $data_sub->fetch(PDO::FETCH_ASSOC);

  
                    $date = implode('-',array_reverse  (explode('/',$annonce['date_enregistrement'])));
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="annonce-box">
                        <div class="annonce-img">
                            <img src="/../data/thumb/<?= $photo['thumb']?>" alt="photo_annonce">
                        </div> 
                        <div class="price_user">
                           <p><?= $annonce['prix']?>€</p> 
                        </div>
                        <div class="like_user">
                            <form action="" method="POST">
                                <input type="hidden" name="idSupr" value="<?=$favori['id_favori']?>">
                                <button type="submit" class="favoris" name='removeFavori'><i class="fas fa-heart"></i></button>
                            </form>
                        </div>
                        <div class="annonce-details">
                            <h4><?= ($annonce['titre_annonce'])?></h4>
                            <div class="description_annonce">
                                <p><?= substr($annonce['description_annonce'],0,255).'...'?></p>
                            </div>
                            <p><i class="fas fa-user"></i> Publié par : <?= $membre['prenom']?></p>
                            <p><i class="fas fa-th-large"></i> : <?= $category['titre_cat']?> / <?= $subcat['titre_subcat']?></p>
                            <p><i class="fas fa-map-marker-alt"></i> : <?= $country['name_country']?> / <?= $region['name_region']?></p>
                        </div>
                        <div class="annoncelink">
                        <a href="../annonce/<?=$annonce['id_annonce'];?>" class="annonce_btn">Voir l'annonce</a>
                        </div>
                    </div>
                </div>
        <?php endforeach;?>
        </div>  

    </div>
<?php else :?>
<div class="container">
    <div class="row">
        <div class="col-12 noFavoris">
            <div class="favorisLink">
                <p>Vous n'avez aucune annonces dans vos favoris</p>
                <a href="../touteslesannonces"> Voir toutes les annonces</a>
            </div>
        </div>
    </div>
</div>
<?php endif;?>
</div>

<?php
include __DIR__.'/assets/includes/footer_user.php';
?>