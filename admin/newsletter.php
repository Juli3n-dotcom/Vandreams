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
if(isset($_POST['delete'])){

    $req =$pdo->prepare(
    'DELETE FROM liste_newsletter
     WHERE :id= id_membre'
 );
 
    $req->bindParam(':id',$_POST['idSupr'],PDO::PARAM_INT);
    $req->execute();

ajouterFlash('success','email supprimé !');
}  

// gestion de l'affichage
$emailsParPage = 15;
$emailsTotalesReq = $pdo->query('SELECT id FROM liste_newsletter');
$emailsTotales = $emailsTotalesReq->rowCount();
$pageTotales = ceil($emailsTotales/$emailsParPage);

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page']<=$pageTotales){
    $_GET['page'] = intval($_GET['page']);
    $pageCourante = $_GET['page'];
}else{
    $pageCourante = 1;
}
$depart = ($pageCourante-1)*$emailsParPage;

$Allemails = $pdo->query('SELECT * FROM liste_newsletter ORDER BY  date_enregistrement DESC LIMIT '.$depart.','.$emailsParPage);

$page_title ='liste Newsletter';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1><i class="far fa-newspaper"></i> Liste Newsletter</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div class="py-5"> <!-- Membre -->
    <div class="container-fluid">
      <div class="row hidden-md-up">
          
        <?php
        $counter =$pdo->query('SELECT COUNT(*) as nb FROM liste_newsletter');
        $data_email = $counter->fetch();
        $totalemail =$data_email['nb'];
        ?>
        <div class="col-md-3">
          <div class="card text-white text-center bg-info"> 
            <div class="card-header">Emails total</div>
                <div class="card-body">
                    <p class="card-text"><?= $totalemail; ?></p>
                </div>
          </div>
        </div>

      </div> <!-- end row -->
    </div> <!-- end container-->
  </div>

<div class="container-fluid">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <th scope="col">#id</th>
            <th scope="col">Email</th>
            <th scope="col">Date d'enregistrement</th>
        </thead>
        <tbody>
        
        <?php foreach(getEmail($pdo) as $email) : ?>
            <?php while($email = $Allemails->fetch()) : ?>
                <tr scope="row" class="table_tr">
                <td scope="row"><?= $email['id'];?></td>
                <td><?= $email['email'];?></td>
                <td><?= $email['date_enregistrement'];?></td>
            <?php endwhile; ?>  
       <?php endforeach;?>
       
        </tbody>
    </table>

    <nav aria-label="...">
        <ul class="pagination justify-content-center">
        <?php
            for($i=1;$i<=$pageTotales;$i++){
            if($i == $pageCourante){
                echo '<li class="page-item active" aria-current="page"><span class="page-link">'.$i.'<span class="sr-only">(current)</span></span></li>';
            }else{
                echo'<li class="page-item"><a class="page-link" href="newsletter.php?page='.$i.'">'.$i.'</a></li> ';
            }
            }
        ?>
</ul>
</nav>

</div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>