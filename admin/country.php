<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/country_functions.php';

// add country
if(isset($_POST['add_country'])){
    if(empty($_POST['name_country'])||strlen($_POST['name_country'])>255){
   ajouterFlash('danger','Le nom doit contenir entre 1 et 255 caractéres.'); 
   }else{

    $req = $pdo ->prepare(
        'INSERT INTO country (name_country)
            VALUES (:name)'
    );
    $req->bindParam(':name',$_POST['name_country']);
    $req->execute();


   ajouterFlash('success','Un nouveau pays a été créer');

   }
}



$page_title ='Gestion des pays';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1>Gestion des Pays</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>


<div id="country">
  <table class="table table-bordered text-center">
    <thead class="thead-dark">
            <th scope="col">id_pays</th>
            <th scope="col">Nom</th>
            <th scope="col">Nombre d'annonces</th>
    </thead>
    <tbody>
        <?php foreach(getCountry($pdo) as $country) : ?>
        <?php
        $id = $country['id_country'];
        $counter =$pdo->query("SELECT COUNT(*) as nb FROM annonces WHERE country_id = '$id'");
        $data = $counter->fetch();
        $totalAnnonces =$data['nb'];
        ?>
            <tr scope="row" class="table_tr">
                <td scope="row"><?php echo $country['id_country'];?></td>
                <td><?php echo $country['name_country'];?></td>
                <td><?= $totalAnnonces; ?></td>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>


<div  class="add" id="add_country">
<h3>Ajouter un pays</h3>
    <div class="container">
        <form action="country.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="name_country" name="name_country" placeholder="Nom du pays">
            </div>

            <input type="submit" name="add_country" value="Ajouter" class="btn btn-success">
        </form>
    </div>
</div>


<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>