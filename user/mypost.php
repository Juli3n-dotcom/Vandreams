<?php
require_once __DIR__ . '/../assets/config/bootstrap.php';
require __DIR__ . '/assets/functions/annonces_by_user.php';


$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);


//traitement uodate

if(isset($_POST['update_annonce'])){

    if(empty($_POST['title_update'])||strlen($_POST['title_update'])>255){
        ajouterFlash('danger','Le titre doit contenir entre 1 et 255 caractéres.');
     
        }elseif (empty($_POST['description_update'])) {
        ajouterFlash('danger','Description manquante.');
     
         }elseif (empty($_POST['price_update'])) {
        ajouterFlash('danger','prix manquant.');
     
        }elseif (!preg_match('~^[0-9-.]+$~',$_POST['price_update'])) {
       ajouterFlash('danger','Merci d\'utiliser que des chiffres pour votre prix');

    
        }elseif (!preg_match('~^[0-9-.]+$~',$_POST['phone_update'])) {
        ajouterFlash('danger','saisir un numéro de téléphone valide'); 

    }else{

    $req_update = $pdo->prepare(
        'UPDATE annonces SET
        titre_annonce = :titre_annonce,
        description_annonce = :description_annonce,
        prix = :prix,
        km = :km,
        telephone = :telephone,
        est_publie = :publie
        WHERE id_annonce = :id
        ');

    $req_update->bindParam(':titre_annonce',htmlspecialchars($_POST['title_update']));
    $req_update->bindParam(':description_annonce',htmlspecialchars($_POST['description_update']));
    $req_update->bindParam(':prix',htmlspecialchars($_POST['price_update']));
    $req_update->bindParam(':km',htmlspecialchars($_POST['km_update']));
    $req_update->bindParam(':telephone',htmlspecialchars($_POST['phone_update']));
    $req_update->bindValue(':publie',isset($_POST['est_publie_update']),PDO::PARAM_BOOL);
    $req_update->bindParam(':id',$_POST['idUpdate'],PDO::PARAM_INT);
    $req_update->execute();

    ajouterFlash('success','Annonce modifiée');
    header('location:mesannonces');  
}

}




// traitement suppression
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

$page_title ='Mes annonces';
include __DIR__.'/assets/includes/header_user.php';
?>

<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="myannonces">
<?php 
    $user = $Membre['id_membre'];
    $count = $pdo->query("SELECT id_annonce FROM annonces WHERE membre_id ='$user'");
    $count->execute();
    $count = $count->rowCount();
?>
 <h1>Mes Annonces</h1>
 <?php if($count > 0):?>
    <div class="container">
        <div class="row">
            <?php foreach(getAnnoncesByUser($pdo,$Membre['id_membre']) as $annonce):?>
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
                        <div class="annonce-details">
                            <h4><?= ($annonce['titre_annonce'])?></h4>
                        <div class="description_annonce">
                            <p><?= substr($annonce['description_annonce'],0,255).'...'?></p>
                        </div>
                            <p><i class="fas fa-user"></i> Publié par : <?= $membre['prenom']?></p>
                            <p><i class="fas fa-th-large"></i> : <?= $category['titre_cat']?> / <?= $subcat['titre_subcat']?></p>
                            <p><i class="fas fa-map-marker-alt"></i> : <?= $country['name_country']?> / <?= $region['name_region']?></p>
                        </div>
                        <div class="annonce_bottom">

                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="../annonce/<?=$annonce['id_annonce'];?>" class="btn seeBtn"><i class="fas fa-eye"></i></a>
                            <a href="annonces.php#id=<?=$annonce['id_annonce']?>" class="btn updateAnnonce" data-toggle="modal" data-target="#<?=$annonce['name']?>update""><i class="fas fa-edit"></i></a>
                            <a href="annonces.php#id=<?=$annonce['id_annonce']?>" class="btn SupAnnonce" data-toggle="modal" data-target="#<?=$annonce['name']?>"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        
                        <!-- Modal update -->
                        <div class="modal fade" id="<?=$annonce['name']?>update" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modification de l'annonce | <?= substr($annonce['titre_annonce'],0,20).'...'?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post">
                                                <label for="title_update" class="label_name_myannonce">Titre de votre annonce : </label>
                                                <input type="text" class="input-field" name="title_update" value="<?= htmlspecialchars($annonce['titre_annonce'])?>">
                                                <hr>
                                                <label for="description_update" class="label_name_myannonce">Description de votre annonce : </label>
                                                <textarea class="input-field" name="description_update" cols="30" rows="10"><?=htmlspecialchars($annonce['description_annonce']??'');?></textarea>
                                                <hr>
                                                <label for="price_update" class="label_name_myannonce">Prix de votre annonce : </label>
                                                <input type="text" class="input-field" name="price_update" value="<?= htmlspecialchars($annonce['prix']??'')?>">
                                                <hr>
                                                <label for="km_update" class="label_name_myannonce">kilométrage : </label>
                                                <input type="text" name="km_update" class="input-field"  value="<?= htmlspecialchars($annonce['km']??'');?>">
                                                <hr>
                                                <label for="price_update" class="label_name_myannonce">Votre numéro de téléphone : </label>
                                                <input type="text" class="input-field" name="phone_update" value="<?= htmlspecialchars($annonce['telephone']??'')?>">
                                                <hr>
                                                <label for="price_update" class="label_name_myannonce">Masquer mon numéro : </label>
                                                <input type="checkbox" class="check-box checkmypost" name="est_publie_update" <?= $annonce['est_publie'] == 1 ? 'checked' : '' ;?>>     
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn annulebtn" data-dismiss="modal">Annuler</button>
                                        <input type="hidden" name="idUpdate" value="<?= $annonce['id_annonce'];?>">
                                        <input type="submit" class="btn updateAnnonce" name="update_annonce" value="Modifier" >
                                    </div>
                                        </form>  
                                    </div>
                            </div>
                            </div>

                           
                            <!-- Modal delete -->
                            <div class="modal fade" id="<?=$annonce['name']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer annonce | <?= substr($annonce['titre_annonce'],0,20).'...'?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post">
                                            
                                                <p class="txtSup">Etes vous sur de vouloir supprimer votre annonce ?</p>
                                            
                                                <div class='confirm_delete' id="confirm_delete">
                                                <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                                                <input type="hidden" name="idSupr" value="<?=$annonce['id_annonce']?>">
                                                <input type="hidden" name="idSupr2" value="<?=$annonce['photo_id']?>">
                                                </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn annulebtn" data-dismiss="modal">Annuler</button>
                                        <input type="submit" class="btn SupAnnonce" name="delete_annonce" value="Supprimer" >
                                    </div>
                                        </form>  
                                    </div>
                            </div>
                        </div>
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
                <p>Vous n'avez aucune annonces en ligne</p>
                <a href="../post"> Déposer une annonce</a>
            </div>
        </div>
    </div>
</div>
<?php endif;?>
</div>

<?php
include __DIR__.'/assets/includes/footer_user.php';
?>