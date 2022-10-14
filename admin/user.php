<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/user_functions.php';

//traitement des modification
if (isset($_POST['update'])){

            $req = $pdo ->prepare(
                'UPDATE membre SET
                statut = :statut,
                confirmation = :confirmation
                WHERE id_membre = :id_membre'
            );
                
                $req->bindValue(':statut',$_POST['statut']);
                $req->bindValue(':confirmation',$_POST['confirmation']);
                $req->bindParam(':id_membre',$_GET['id'],PDO::PARAM_INT);
                $req->execute();
    
                ajouterFlash('success','Modification effectuée');    
}

// tratement suppression
if(isset($_POST['delete_membre'])){

    if(!isset($_POST['delete_check'])){
        ajouterFlash('danger','Merci de confirmer la suppression !');
    
      }else{

    $req =$pdo->prepare(
    'DELETE FROM membre
     WHERE :id= id_membre'
 );
 
    $req->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
    $req->execute();

ajouterFlash('success','membre supprimée !');
      }
}  

// gestion de l'affichage
$membresParPage = 15;
$membresTotalesReq = $pdo->query('SELECT id_membre FROM membre');
$membresTotales = $membresTotalesReq->rowCount();
$pageTotales = ceil($membresTotales/$membresParPage);

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page']<=$pageTotales){
    $_GET['page'] = intval($_GET['page']);
    $pageCourante = $_GET['page'];
}else{
    $pageCourante = 1;
}
$depart = ($pageCourante-1)*$membresParPage;

$Allmembres = $pdo->query('SELECT * FROM membre ORDER BY date_enregistrement DESC LIMIT '.$depart.','.$membresParPage);

$page_title ='Gestion des membres';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1><i class="fas fa-user"></i> Gestion des Membres</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="py-5"> <!-- Membre -->
    <div class="container-fluid">
      <div class="row hidden-md-up">
          
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

  <div class="container-fluid">
    <form method="post">
        <div class="form-group">
            <input type="search" class="form-control mb-2" id="search_user" name="search_user" placeholder="Rechercher un membre">
            <input type="submit" value="Rechercher" id="search" class='btn btn-info'>
        </div>
    </form>
  </div>
        
      

  <div id="resultat">

<div class="container-fluid">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <th scope="col">#id</th>
            <th scope="col">Email</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Statut</th>
            <th scope="col">Confirmation</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
        </thead>
        <tbody>
        <?php while($Membre = $Allmembres->fetch()) : ?>
                <tr scope="row" class="table_tr">
                <td scope="row"><?= $Membre['id_membre'];?></td>
                <td><?= $Membre['email'];?></td>
                <td><?= $Membre['nom'];?></td>
                <td><?= $Membre['prenom'];?></td>
                <td>
                    <?php if ($Membre['statut'] == 0):?>
                        <?= '<p class="btn btn-primary">User</p>';?>
                    <?php else:?>
                        <?= '<p class="btn btn-dark">Admin</p>';?>
                    <?php endif;?>         
                </td>
                <td>
                    <?php if ($Membre['confirmation'] == 0):?>
                        <?= '<p class="btn btn-danger">Non</p>';?>
                    <?php else:?>
                        <?= '<p class="btn btn-success">Oui</p>';?>
                    <?php endif;?>         
                </td>
                <td>
                <a href="user.php?id=<?=$Membre['id_membre'];?>" class="btn btn-info" data-toggle="modal" data-target="#<?= $Membre['name'];?>"> <i class="fas fa-edit"></i> Update </a>

                <!-- Modal -->

                <div class="modal fade" id="<?= $Membre['name'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" >Modification Membre | #<?= $Membre['id_membre']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="user.php?id=<?=$Membre['id_membre'];?>" method="post" class="form-inline">
                                            <div class="container">
                                                <div class="row">

                                                    <div class="col form-group form-control-lg">
                                                        <label class="my-1 mr-2" for="confirmation">Confirmation Actuelle : <?= $Membre['confirmation'] == 0 ? '<p class="btn btn-danger">Non</p>' :'<p class="btn btn-success">Oui</p>' ;?></label>
                                                        <select class="custom-select" name="confirmation">
                                                            <option> Modifier </option>
                                                            <option value="<?= 1 ?>">Oui</option>
                                                            <option value="<?= 0 ?>">Non</option>
                                                        </select>
                                                    </div>
                                     
                                                    <div class="col form-group form-control-lg">
                                                        <label class="my-1 mr-2" for="statut">Statut actuel : <?= $Membre['statut'] == 0 ? '<p class="btn btn-primary">User</p>' :'<p class="btn btn-dark">Admin</p>' ;?></label>
                                                        <select class="custom-select" name="statut">
                                                            <option> Modifier </option>
                                                            <option value="<?= ROLE_ADMIN ?>">Admin</option>
                                                            <option value="<?= ROLE_USER ?>">User</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="modal__submit" name="update" value="Valider" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
                </td> <!--  fin update-->
                <td>
                <a href="user.php?id=<?=$Membre['id_membre'];?>" class="btn btn-danger" data-toggle="modal" data-target="#<?= $Membre['name'];?>sup"><i class='fas fa-trash-alt'></i> Delete</a>

                        <!-- Modal delete -->

                        <div class="modal fade" id="<?= $Membre['name'];?>sup" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer Membre | #<?= $Membre['id_membre']??'';?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="user.php?id=<?=$Membre['id_membre'];?>" method="post">
                                            
                                                <p class="mb-2">Etes vous sur de vouloir supprimer le membre #<?= $Membre['id_membre'];?> ?</p>
                                            
                                                <div class='confirm_delete' id="confirm_delete">
                                                <input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>
                                                <input type="hidden" name="idSupr" value="<?=$Membre['id_membre'];?>">
                                                </div>
                                         </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-danger" name="delete_membre" value="Supprimer" >
                                    </div>
                                        </form>  
                                    </div>
                                </div>
                        </div>
                    </td>
        <?php endwhile; ?>
        </tbody>
    </table>

</div><!-- fin resultat-->

    <nav aria-label="...">
        <ul class="pagination justify-content-center">
        <?php
            for($i=1;$i<=$pageTotales;$i++){
            if($i == $pageCourante){
                echo '<li class="page-item active" aria-current="page"><span class="page-link">'.$i.'<span class="sr-only">(current)</span></span></li>';
            }else{
                echo'<li class="page-item"><a class="page-link" href="user.php?page='.$i.'">'.$i.'</a></li> ';
            }
            }
        ?>
</ul>
</nav>

</div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>