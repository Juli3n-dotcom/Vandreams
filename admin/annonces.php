<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/annonces_functions.php';

// tratement suppression
if(isset($_POST['delete_annonce'])){

  if(!isset($_POST['delete_check'])){
    ajouterFlash('danger','Merci de confirmer la suppression !');

  }else{

    $id_photo = $_POST['idSupr2'];

    $data = $pdo->query("SELECT * FROM photo WHERE id_photo = '$id_photo'");
    $photo = $data->fetch(PDO::FETCH_ASSOC);

    
    $file = "../data/thumb/";
    opendir($file);
    
    unlink($file.$photo['thumb']);
    // closedir($file);

    $file2 = "../data/";
    opendir($file2);
    
    unlink($file2.$photo['photo1']);
    unlink($file2.$photo['photo2']);
    unlink($file2.$photo['photo3']);
    // closedir($file2);
    
      $req2 =$pdo->prepare(
        'DELETE FROM photo
         WHERE :id= id_photo'
      );
      
        $req2->bindParam(':id',$_POST['idSupr2'],PDO::PARAM_INT);
        $req2->execute();



      $req3 =$pdo->prepare(
          'DELETE FROM annonces
           WHERE :id= id_annonce'
      );
        
        $req3->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
        $req3->execute();
    
    ajouterFlash('success','annonce supprimée !');
  }

  
}  

$page_title ='Gestion des annonces';
include __DIR__.'/assets/includes/header_admin.php';
?>

<div class="title_page">
    <h1><i class="fas fa-archive"></i> Gestion des annonces</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="py-5"> <!-- Membre -->
    <div class="container-fluid">
      <div class="row hidden-md-up">
          
        <?php
        $counter =$pdo->query('SELECT COUNT(*) as nb FROM annonces');
        $data_annonces = $counter->fetch();
        $totalAnnonces =$data_annonces['nb'];
        ?>
        <div class="col-md-3">
          <div class="card text-white text-center bg-info"> 
            <div class="card-header">Annonces total</div>
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

  <div class="container-fluid">
    <div class="row">
<?php foreach(getAnnonces($pdo) as $annonce):?>
    <div class="card col-md-4 mb-4 <?php if($annonce['est_signal']==1):?>bg-warning<?php endif;?>" style="width: 8rem;">
    <div class="card-header">Annonce #<?=$annonce['id_annonce']?></div>
    <?php
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
    ?>
      <img src="../data/thumb/<?= $photo['photo1']?>" class="card-img-top" alt="image_annonce">
      <div class="card-body">
        <h4 class="card-title mb-3"><?=$annonce['titre_annonce']?></h4>
        <p class="card-text mb-3">Publiée par :  <?=$membre['prenom']?></p>
        <p class="card-text mb-3">Catégorie :  <?=$category['titre']?> /  <?=$subcat['titre']?></p>
        <p class="card-text mb-3">Localisation :  <?=$country['name']?> / <?=$region['name']?></p>
        <p class="card-text mb-3"><small class="text-muted">Publiée le: <?=$annonce['date_enregistrement']?></small></p>
        <a href="../fiche.php?id=<?=$annonce['id_annonce'];?>" class="btn btn-primary">Voir l'annonce</a>
      </div>
      <div class="card-footer">
      <a href="annonces.php#id=<?=$annonce['id_annonce']?>" class="btn btn-danger"data-toggle="modal" data-target="#<?=$annonce['name']?>"><i class='fas fa-trash-alt'></i> Delete</a>

     <!-- Modal delete -->

     <div class="modal fade" id="<?=$annonce['name']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer annonce | #<?=$annonce['id_annonce']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="annonces.php?id=<?=$annonce['id_annonce'];?>" method="post">
                                            
                                                <p class="mb-2">Etes vous sur de vouloir supprimer l'annonce #<?=$annonce['id_annonce']?> ?</p>
                                            
                                                <div class='confirm_delete' id="confirm_delete">
                                                <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                                                <input type="hidden" name="idSupr" value="<?=$annonce['id_annonce']?>">
                                                <input type="hidden" name="idSupr2" value="<?=$annonce['photo_id']?>">
                                                </div>
                                         </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-danger" name="delete_annonce" value="Supprimer" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
      </div>
    </div>
<?php endforeach;?>

    </div>
  </div>

<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>