<?php
require_once __DIR__ . '/assets/config/bootstrap.php';
require_once __DIR__ . '/assets/functions/post.php';
require_once __DIR__ . '/assets/functions/annonces.php';
require_once __DIR__ . '/assets/functions/membre_function.php';


$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);

if(isset($_POST['noUser'])){
    setcookie('favindex', true, time()+3600, '/', null,false, true);
    sleep(1);
      header('location:login');
    }

if(isset($_POST['addFavori'])){
    ajouterFlash('success','Annonce sauvegardée');
}


require_once __DIR__ . '/assets/functions/annonces.php';
$page_title ='Accueil';
include __DIR__.'/assets/includes/header_index.php';
include __DIR__.'/assets/includes/flash.php';
?>


<section class="hero">
        
            
        <p class="coords">N 48° 42' 2.571" / E 2° 31' 1.023"</p>

<div class="ellipse-container">
    <h2 class="greeting">Van Dreams</h2>
    <div class="ellipse ellipse__outer--thin">
        <div class="ellipse ellipse__orbit"></div>
    </div>
    <div class="ellipse ellipse__outer--thick"></div>
           
        
</section>

<?php
$count = $pdo->query("SELECT id_annonce FROM annonces");
$count->execute();
$count = $count->rowCount();
?>
<?php if($count > 0):?>
<section class="container">
    <div class="title-heading">
        <h3>Nouveautés</h3>
            <h1>Découvrez les derniéres annonces</h1>
                <p>Venez trouver votre bonheur parmis des petits nouveaux du site</p>
    </div>
    <div class="row">
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach(getAnnonces($pdo) as $annonce):?>
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
                <li class="glide__slide">
                    <div class="col-md-6 col-lg-4 card_carousel">
                    <div class="annonce-box">
                        <div class="annonce-img">
                            <img src="/data/thumb/<?= $photo['thumb']?>" alt="photo_annonce">
                        </div>
                        <div class="price">
                            <p><?= $annonce['prix']?>€</p> 
                        </div>
                        <div class="like">
                            <div class="resultat<?=$annonce['id_annonce']?>">
                                <?php
                                    if($Membre === null){
                                        echo '<form action="" method="POST">
                                                <button type="submit" class="noUser" name="noUser"><i class="far fa-heart"></i></button>
                                            </form>';  
                                    }else{
                                        $favori = getfavori($pdo, $Membre['id_membre'], $annonce['id_annonce']);
    
                                        if($favori == false){
                                            echo '<form action="" method="POST">
                                                    <input type="hidden" name="iduser" id="iduser" value="'.$Membre["id_membre"].'">
                                                    <input type="hidden" name="idannonce" id="idannonce" value="'.$annonce["id_annonce"].'">
                                                    <button type="submit" class="favoris" id="addFavori" name="addFavori"><i class="far fa-heart"></i></button>
                                                </form>';   
                                        }else{
                                            echo '<form action="" method="POST">
                                                    <input type="hidden" id="idSupr" name="idSupr" value="'.$favori.'">
                                                    <input type="hidden" name="iduser" id="iduser" value="'.$Membre["id_membre"].'">
                                                    <input type="hidden" name="idannonce" id="idannonce" value="'.$annonce["id_annonce"].'">
                                                     <button type="submit" class="removefavori" id="removeFavori" name="removeFavori"><i class="fas fa-heart"></i></button>
                                                </form>';
                                        
                                        }
                                    }
                                ?> 
                            </div> <!-- fin resultat-->
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
                            <a href="annonce/<?=$annonce['id_annonce'];?>" class="annonce_btn">Voir l'annonce</a>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>            
        </ul>
    </div>
  <div class="glide__arrows" data-glide-el="controls">
    <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><i class="fas fa-chevron-left"></i></button>
    <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><i class="fas fa-chevron-right"></i></button>
  </div>
</div>
</div>
<a href="touteslesannonces" id="index_post"class="index_link index_post">Voir toutes les annonces</a>
</section>

<section class="part2">
        <div class="container">
            <div class="block2">
                <div class="block-text-box">
                    <h3>
                        Et si vous aussi vous vendiez votre van sur VanDreams?
                    </h3>
                </div>
                <div class="block-depot">
                   <a href="post" class="index_link">Déposer une annonce</a>
                </div>
            </div>
        </div>
</section>

<section class="container part3">
    <div class="title-heading">
        <h3>Les aménagés</h3>
            <h1>Envie de partir directement?</h1>
                <p>Venez découvrir une selection de véhicules déjà aménagés</p>
    </div>
    <div class="row">
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach(getAnnoncesSubCat($pdo) as $annonce):?>
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
                <li class="glide__slide">
                    <div class="col-md-6 col-lg-4 card_carousel">
                    <div class="annonce-box">
                        <div class="annonce-img">
                            <img src="/data/thumb/<?= $photo['thumb']?>" alt="photo_annonce">
                        </div>
                        <div class="price">
                            <p><?= $annonce['prix']?>€</p> 
                        </div>
                        <div class="like">
                            <div class="resultat<?=$annonce['id_annonce']?>">
                        <?php
                    if($Membre === null){
                        echo '<form action="" method="POST">
                                <button type="submit" class="noUser" name="noUser"><i class="far fa-heart"></i></button>
                            </form>';  
                    }else{
                        $favori = getfavori($pdo, $Membre['id_membre'], $annonce['id_annonce']);
    
                        if($favori == false){
                            echo '<form action="" method="POST">
                                    <input type="hidden" name="iduser" id="iduser" value="'.$Membre["id_membre"].'">
                                    <input type="hidden" name="idannonce" id="idannonce" value="'.$annonce["id_annonce"].'">
                                    <button type="submit" class="favoris" id="addFavori" name="addFavori"><i class="far fa-heart"></i></button>
                                </form>';   
                        }else{
                            echo '<form action="" method="POST">
                                    <input type="hidden" id="idSupr" name="idSupr" value="'.$favori.'">
                                    <input type="hidden" name="iduser" id="iduser" value="'.$Membre["id_membre"].'">
                                    <input type="hidden" name="idannonce" id="idannonce" value="'.$annonce["id_annonce"].'">
                                     <button type="submit" class="removefavori" id="removeFavori" name="removeFavori"><i class="fas fa-heart"></i></button>
                                </form>';
                        }
                    }
                ?>
                            </div>
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
                            <a href="annonce/<?=$annonce['id_annonce'];?>" class="annonce_btn">Voir l'annonce</a>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>            
        </ul>
    </div>
  <div class="glide__arrows" data-glide-el="controls">
    <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><i class="fas fa-chevron-left"></i></button>
    <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><i class="fas fa-chevron-right"></i></button>
  </div>
</div>
</div>
</section>

<?php else :?>

    <section class="part_nopost">
        <div class="container">
        <div class="title-heading">
                <h3>Bonjour</h3>
                 <h1>Nous venons d'ouvrir</h1>
                <p>Soyez le premier a poster une annonce</p>
        </div>
        <div class="block-depot">
                   <a href="post" class="index_link">Déposer une annonce</a>
                </div>
            </div>
        </div>
</section>
<?php endif;?>

<section class="part4">
        <div class="container">
            <div class="block2">
                <div class="block-text-box">
                    <h3>
                       Et si nous restions en contact?
                    </h3>
                </div>
                <div class="block-depot">
                    <form method="post">
                        <div>
                            <input type="email" name="email_news" class="input-field-footer" placeholder="Entrer votre email">
                            <input type="hidden" name="ipUser" value="<?= getIp() ?>">
                        </div>
                            <button type="submit" class='news index_link' name="news_submit_footer"> S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
</section>

<section class="part5">
        <div class="container">
        <div class="title-heading">
            <h3>Qui sommes nous</h3>
                <h2>Et si nous faisons plus connaissance?</h2>
                
            </div>
                <div class="block-depot">
                    <div class="row">
                        <div class="col-md-6" id="logo_presentation">
                            <img src="assets/img/logo3.png" alt="logo">
                        </div>
                        <div class="col-md-6 text_presentation">
                            <p>
                            Tout est parti d’une belle rencontre et d’un projet de vivre l’aventure vanlife, 
                            incluant l’achat d’un van aménagé.
                            </p>
                            <p>
                            Ce projet est vite devenu un parcours du combattant, 
                            quand il a fallu faire le tri entre des sites connus de petites annonces avec surtout des
                            annonces de professionnels a des prix exorbitants, ou encore la nuée de pages 
                             sur les réseaux sociaux dédié à la vente de vans aménagés.
                            </p>
                            <p>
                            Je me suis rendu compte qu’il n’existait pas d’endroit regroupant 
                            facilement des annonces spécialisées de vans aménagés entre vanlifeurs. 
                            </p>
                            <p>
                            Le projet de Van Dreams a vu le jours durant le confinement, 
                            le but étant de créer un site de petites annonces pour mettre en
                             relation les vanlifeurs, pouvant facilement vendre ou trouver 
                             leurs véhicules pour vivre à leur tour l’aventure vanlife.
                            </p>
                            <p class="devise">
                                Le premier site de petites annonces entre vanlifeurs
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
<script type="text/javascript" src="assets/js/index.js"></script>
<?php
include __DIR__.'/assets/includes/cookie.php';
include __DIR__.'/assets/includes/footer.php';
?>