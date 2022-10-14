<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/cat_functions.php';



// add categorie
if(isset($_POST['add'])){


    if(empty($_POST['titre'])||strlen($_POST['titre'])>255){
   ajouterFlash('danger','Le titre doit contenir entre 1 et 255 caractéres.');

   }elseif (empty($_POST['motscles'])) {
   ajouterFlash('danger','il manque les mots clés.');

   }else{

    $explode = explode(' ',$_POST['titre']);
    $name = $explode[0].$explode[1].$explode[2].$explode[3];

    $req = $pdo ->prepare(
        'INSERT INTO sub_category (name,titre, motscles)
            VALUES (:name,:titre,:motscles)'
    );
    $req->bindParam(':titre',$_POST['titre']);
    $req->bindParam(':name',$name);
    $req->bindParam(':motscles',$_POST['motscles']);
    $req->execute();


   ajouterFlash('success','une nouvelle catégorie a été créer');

   }
}

// update categorie
if(isset($_POST['modifier'])){

    if(empty($_POST['titre'])||strlen($_POST['titre'])>255){
   ajouterFlash('danger','Le titre doit contenir entre 1 et 255 caractéres.');

   }elseif (empty($_POST['motscles'])) {
   ajouterFlash('danger','il manque les mots clés.');

   }else{

    $explode = explode(' ',$_POST['titre']);
    $name = $explode[0].$explode[1].$explode[2].$explode[3];

    $req = $pdo ->prepare(
        'UPDATE sub_category SET
         titre_subcat = :titre,
         name = :name,
         motscles = :motscles
          WHERE id_sub_cat = :id_sub_cat'
    );
    $req->bindParam(':titre',$_POST['titre']);
    $req->bindParam(':name',$name);
    $req->bindParam(':motscles',$_POST['motscles']);
    $req->bindParam(':id_sub_cat',$_GET['id'],PDO::PARAM_INT);
    $req->execute();

   ajouterFlash('success','Catégorie modifiée');

   }
}

//delete categorie
if(isset($_POST['delete'])){
    if(empty($_POST['delete_check'])){
        ajouterFlash('danger','Vous devez confirmer la suppresion');
    }else{
        $req =$pdo->prepare(
            'DELETE FROM sub_category
             WHERE :id= id_sub_cat'
         );
         
            $req->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
            $req->execute();

ajouterFlash('success','catégorie supprimée !');
    }
}

$page_title ='Gestion des sous categories';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1>Gestion des sous catégories</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="container">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <th scope="col">#id</th>
            <th scope="col">Titre</th>
            <th scope="col">Mots Clés</th>
            <th scope="col">Nombre d'annonces</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </thead>
        <tbody>
            <?php foreach(getSubCategory($pdo) as $subcat) : ?>
                <tr scope="row" class="table_tr">
                    <td scope="row"><?php echo $subcat['id_sub_cat'];?></td>
                    <td><?php echo $subcat['titre_subcat'];?></td>
                    <td><?php echo $subcat['motscles'];?></td>
                    <?php
                    $id = $subcat['id_sub_cat'];
                    $counter =$pdo->query("SELECT COUNT(*) as nb FROM annonces WHERE subcat_id = '$id'");
                    $data = $counter->fetch();
                    $totalAnnonces =$data['nb'];
                    ?>
                    <td><?= $totalAnnonces; ?></td>
                    <td>
                        <a href="sub_cat.php?id=<?=$subcat['id_sub_cat'];?>" class="btn btn-info" data-toggle="modal" data-target="#<?= $subcat['name'];?>"> <i class="fas fa-edit"></i> Update </a>

                        <!-- Modal -->

                        <div class="modal fade" id="<?= $subcat['name'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" >Modification Catégorie | <?= $subcat['titre_subcat']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="category.php?id=<?=$subcat['id_sub_cat'];?>" method="post">
                                            <div class="form-group">
                                                <label for="titre">Titre :</label>
                                                <input type="text" class="form-control" id="titre" name="titre"  value="<?= $cat['titre']??'';?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="motscles">Les Mots Clés : </label>
                                                <textarea class="form-control" id="motscles" name="motscles" rows="3" placeholder="Insertion de mots clés" value="<?= $cat['motscles']??'';?>"></textarea>
                                            </div>
                                         </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-primary" name="modifier" value="Modifier" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
                    </td> <!-- fin td update-->
                    <td><a href="category.php?id=<?=$subcat['id_sub_cat'];?>" class="btn btn-danger" data-toggle="modal" data-target="#<?= $subcat['name'];?>sup"><i class='fas fa-trash-alt'></i> Delete</a>

                        <!-- Modal delete -->

                        <div class="modal fade" id="<?= $subcat['name'];?>sup" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer Catégorie | <?= $subcat['titre_subcat']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="category.php?id=<?=$subcat['id_sub_cat'];?>" method="post">
                                            
                                                <p class="mb-2">Etes vous sur de vouloir supprimer la catégorie?</p>
                                            
                                                <div class='confirm_delete' id="confirm_delete">
                                                <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                                                <input type="hidden" name="idSupr" value="<?=$subcat['id_sub_cat'];?>">
                                                </div>
                                         </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-danger" name="delete" value="Supprimer" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="add" id="add_cat">
<h3>Ajouter une sous catégorie</h3>
    <div class="container">
        <form action="subcat.php" method="post">

        
        </div>
            <div class="form-group">
                <input type="text" class="form-control"  name="titre" placeholder="Nom de la sous catégorie">
            </div>

            <div class="form-group">
                <label for="motscles">Les Mots Clés : </label>
                <textarea class="form-control" name="motscles" rows="3" placeholder="Insertion de mots clés"></textarea>
             </div>

            <input type="submit" name="add" value="Ajouter" class="btn btn-success">
        </form>
    </div>
</div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>