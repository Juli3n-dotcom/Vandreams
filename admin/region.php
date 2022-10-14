<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/country_functions.php';

// add Region
if(isset($_POST['add_region'])){
    if(empty($_POST['name_region'])||strlen($_POST['name_region'])>255){
   ajouterFlash('danger','Le nom doit contenir entre 1 et 255 caractéres.'); 
    }elseif(empty($_POST['country'])){
    ajouterFlash('danger','Veuillez un pays.'); 
}   else{

    $req = $pdo ->prepare(
        'INSERT INTO region (name_region, country_id)
            VALUES (:name, :country_id)'
    );
    $req->bindParam(':name',$_POST['name_region']);
    $req->bindParam(':country_id',$_POST['country']);
    $req->execute();


   ajouterFlash('success','Une nouvelle région a été créer');

   }
}

// gestion de l'affichage
$regionsParPage = 10;
$regionsTotalesReq = $pdo->query('SELECT id_region FROM region');
$regionsTotales = $regionsTotalesReq->rowCount();
$pageTotales = ceil($regionsTotales/$regionsParPage);

if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page']<=$pageTotales){
    $_GET['page'] = intval($_GET['page']);
    $pageCourante = $_GET['page'];
}else{
    $pageCourante = 1;
}
$depart = ($pageCourante-1)*$regionsParPage;

$Allregions = $pdo->query('SELECT * FROM region ORDER BY country_id ASC LIMIT '.$depart.','.$regionsParPage);

$page_title ='Gestion des régions';
include __DIR__.'/assets/includes/header_admin.php';
?>


<div class="title_page">
    <h1>Gestion des Régions</h1>
</div>
<?php include __DIR__.'/../assets/includes/flash.php';?>

<div id="regions">
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <th scope="col">id_region</th>
            <th scope="col">Nom</th>
            <th scope="col">Pays</th>
            <th scope="col">Nombre d'annonces</th>
        </thead>
    <tbody>
        <?php foreach(getRegion($pdo) as $region) : ?>
            <?php while($region = $Allregions->fetch()) : ?>
            <tr scope="row" class="table_tr">
                <td scope="row"><?php echo $region['id_region'];?></td>
                <td><?php echo $region['name_region'];?></td>
                <?php
                    $id = $region['country_id'];
                    $data = $pdo->query("SELECT name_country FROM country WHERE id_country = '$id'");
                    $name_country = $data->fetch(PDO::FETCH_ASSOC);
                    ?>
                <td><?php echo $name_country['name_country'];?></td>
                <?php
                $id = $region['id_region'];
                $counter =$pdo->query("SELECT COUNT(*) as nb FROM annonces WHERE region_id = '$id'");
                $data = $counter->fetch();
                $totalAnnonces =$data['nb'];
                ?>
                <td><?= $totalAnnonces; ?></td>
                <?php endwhile; ?>  
        <?php endforeach; ?>

    </tbody>
  </table>
  <nav aria-label="...">
            <ul class="pagination justify-content-center">
            <?php
            for($i=1;$i<=$pageTotales;$i++){
                if($i == $pageCourante){
                    echo '<li class="page-item active" aria-current="page"><span class="page-link">'
                    .$i.
                    '<span class="sr-only">(current)</span>
                    </span>
                    </li>';
                }else{
                    echo'<li class="page-item"><a class="page-link" href="region.php?page='.$i.'">'.$i.'</a></li> ';
                }
            }
            ?>
            </ul>
        </nav>



<div class="add" id="add_region">
<h3>Ajouter une région</h3>
    <div class="container">
        <form action="region.php" method="post">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Pays</label>
        </div>
            <select class="custom-select" id="inputGroupSelect01" name="country">
                <option selected>Choisir...</option>
                <?php foreach(getCountry($pdo) as $country) : ?>
                <option value="<?=$country['id_country'];?>"><?=$country['name_country'];?></option>
                <?php endforeach; ?>
            </select>
        </div>
            <div class="form-group">
                <input type="text" class="form-control" id="name_region" name="name_region" placeholder="Nom de la region">
            </div>

            <input type="submit" name="add_region" value="Ajouter" class="btn btn-success">
        </form>
    </div>
</div>
<?php
include __DIR__.'/assets/includes/footer_admin.php';
?>