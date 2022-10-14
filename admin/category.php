<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/cat_functions.php';



// add categorie
if(isset($_POST['ajouter'])){


    if(empty($_POST['titre'])||strlen($_POST['titre'])>255){
   ajouterFlash('danger','Le titre doit contenir entre 1 et 255 caractéres.');

   }elseif (empty($_POST['motscles'])) {
   ajouterFlash('danger','il manque les mots clés.');

   }else{

    $explode = explode(' ',$_POST['titre']);
    $name = $explode[0].$explode[1].$explode[2].$explode[3];

    $req = $pdo ->prepare(
        'INSERT INTO category (name,titre, motscles)
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
        'UPDATE category SET
         titre_cat = :titre,
         name = :name,
         motscles = :motscles
          WHERE id_category = :id_category'
    );
    $req->bindParam(':titre',$_POST['titre']);
    $req->bindParam(':name',$name);
    $req->bindParam(':motscles',$_POST['motscles']);
    $req->bindParam(':id_category',$_GET['id'],PDO::PARAM_INT);
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
            'DELETE FROM category
             WHERE :id= id_category'
         );
         
            $req->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
            $req->execute();

ajouterFlash('success','catégorie supprimée !');
    }
}

$page_title ='Gestion des categories';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1>Gestion des catégories</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="container">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <th scope="col">id_category</th>
            <th scope="col">Titre</th>
            <th scope="col">Mots Clés</th>
            <th scope="col">Nombre d'annonces</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </thead>
        <tbody>
            <?php foreach(getCategory($pdo) as $cat) : ?>
                <tr scope="row" class="table_tr">
                    <td scope="row"><?php echo $cat['id_category'];?></td>
                    <td><?php echo $cat['titre_cat'];?></td>
                    <td><?php echo $cat['motscles'];?></td>
                    <?php
                    $id = $cat['id_category'];;
                    $counter =$pdo->query("SELECT COUNT(*) as nb FROM annonces WHERE category_id = '$id'");
                    $data = $counter->fetch();
                    $totalAnnonces =$data['nb'];
                    ?>
                    <td><?= $totalAnnonces; ?></td>
                    <td>
                        <a href="category.php?id=<?=$cat['id_category'];?>" class="btn btn-info" data-toggle="modal" data-target="#<?= $cat['name'];?>"> <i class="fas fa-edit"></i> Update </a>

                        <!-- Modal -->

                        <div class="modal fade" id="<?= $cat['name'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" >Modification Catégorie | <?= $cat['titre_cat']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="category.php?id=<?=$cat['id_category'];?>" method="post">
                                            <div class="form-group">
                                                <label for="titre">Titre :</label>
                                                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de la salle" value="<?= $cat['titre']??'';?>">
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
                    <td><a href="category.php?id=<?=$cat['id_category'];?>" class="btn btn-danger" data-toggle="modal" data-target="#<?= $cat['name'];?>sup"><i class='fas fa-trash-alt'></i> Delete</a>

                        <!-- Modal delete -->

                        <div class="modal fade" id="<?= $cat['name'];?>sup" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer Catégorie | <?= $cat['titre_cat']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="category.php?id=<?=$cat['id_category'];?>" method="post">
                                            
                                                <p class="mb-2">Etes vous sur de vouloir supprimer la catégorie?</p>
                                            
                                                <div class='confirm_delete' id="confirm_delete">
                                                <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                                                <input type="hidden" name="idSupr" value="<?=$cat['id_category'];?>">
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


<div class="container add" id="add_cat">
    <h3>Ajouter une catégorie</h3>
<form action="category.php" method="post">
  <div class="form-group">
    <label for="titre">Titre :</label>
    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de la catégorie" value="<?= $_POST['titre']??'';?>">
  </div>

  <div class="form-group">
    <label for="motscles">Les Mots Clés : </label>
    <textarea class="form-control" id="motscles" name="motscles" rows="3" placeholder="Insertion de mots clés"></textarea>
  </div>

  <input type="submit" name="ajouter" value="Ajouter" class="btn btn-success">
</form>
</div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>