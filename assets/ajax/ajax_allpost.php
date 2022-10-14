<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../functions/post.php';
require_once __DIR__ . '/../functions/annonces.php';
require_once __DIR__ . '/../functions/membre_function.php';


$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);

$second_where = false;

$requete = 'SELECT a.id_annonce, 
                    a.titre_annonce, 
                    a.description_annonce, 
                    a.prix,
                    a.category_id,
                    a.subcat_id, 
                    a.country_id,
                    a.region_id,
                    a.membre_id,
                    a.photo_id,
                    i.id_photo,
                    i.thumb,
                    m.prenom,
                    p.id_country,
                    p.name_country,
                    r.id_region,
                    r.name_region,
                    c.titre_cat,
                    s.titre_subcat
            FROM annonces a 
            LEFT JOIN photo i ON a.photo_id = i.id_photo
            LEFT JOIN category c ON a.category_id = c.id_category
            LEFT JOIN sub_category s ON a.subcat_id = s.id_sub_cat
            LEFT JOIN country p ON a.country_id = p.id_country
            LEFT JOIN region r ON a.region_id = r.id_region
            LEFT JOIN membre m ON a.membre_id = m.id_membre
            ';

if(isset($_POST['category']) && $_POST['category'] != 'A'){
    $requete .= ' WHERE a.category_id = :id_category';
    $second_where = true;
}

if(isset($_POST['subcat']) && $_POST['subcat'] != 'A'){
    if($second_where == true){
        $requete .=' AND a.subcat_id = :id_sub_cat';
    }else{
        $requete .=' WHERE a.subcat_id = :id_sub_cat';
    }
    $second_where = true;
}

if(isset($_POST['country']) && $_POST['country'] != 'A'){
    if($second_where == true){
        $requete .=' AND a.country_id = :id_country';
    }else{
        $requete .=' WHERE a.country_id = :id_country';
    }
    $second_where = true;
}

if(isset($_POST['regions']) && $_POST['regions'] != 'A'){
    if($second_where == true){
        $requete .=' AND a.region_id = :id_region';
    }else{
        $requete .= ' WHERE a.region_id = :id_region';
    }
    $second_where = true;
}

// if(isset($_POST['prix_min'])){
//     if($second_where == true){
//         $requete .= ' AND a.prix > :pmin';
//     }else{
//         $requete .= ' WHERE a.prix > :pmin';
//     }
//     $second_where = true;
// }

// if(isset($_POST['prix_max'])){
//     if($second_where == true){
//         $requete .=' AND a.prix < :pmax';
//     }else{
//         $requete .= ' WHERE a.prix < :pmax';
//     }
//     $second_where = true;
// }

$requete .=' ORDER BY a.date_enregistrement DESC';
// $requete .='LIMIT 1';

//préparation de la requete
$req = $pdo->prepare($requete);


if(isset($_POST['category']) && $_POST['category'] != 'A'){
    $req->bindParam(':id_category', $_POST['category']);
}
if(isset($_POST['subcat']) && $_POST['subcat'] != 'A'){
    $req->bindParam(':id_sub_cat', $_POST['subcat']);
}
if(isset($_POST['country']) && $_POST['country'] != 'A'){
    $req->bindParam(':id_country', $_POST['country']);
}
if(isset($_POST['regions']) && $_POST['regions'] != 'A'){
    $req->bindParam(':id_region', $_POST['regions']);
}
// if(isset($_POST['prix_min'])){
//     $req->bindParam(':pmin', $_POST['prix_min']);
// }
// if(isset($_POST['prix_max'])){
//     $req->bindParam(':pmax', $_POST['prix_max']);
// }

$req->execute();
$count = $req->rowCount();

$resultat = '';
if($count > 0){
    $resultat .= '<div class="allannonces">';
        $resultat .= '<div class="container">';
            $resultat .= '<div class="row">';
        while($annonce = $req->fetch(PDO::FETCH_ASSOC)){
            $photo = ($annonce['thumb'] != null) ? $annonce['thumb'] : 'img-vide.png';
    $resultat .= '<div class="col-md-6 col-lg-4">';
        $resultat .= '<div class="annonce-box">';
            $resultat .= '<div class="annonce-img">';
                $resultat .= '<img src="data/thumb/'.$photo.'" alt="photo_annonce">';
            $resultat .= '</div>';
    
            $resultat .= '<div class="price">';
                $resultat .= '<p>'.$annonce['prix'].'€</p> ';
            $resultat .= '</div>';
    
            $resultat .= '<div class="like">';
            if($Membre === null){
                $resultat .= '<form action="" method="POST">
                        <button type="submit" class="favoris" name="noUser"><i class="far fa-heart"></i></button>
                    </form>';  
            }else{
                $favori = getfavori($pdo, $Membre['id_membre'], $annonce['id_annonce']);
    
                if($favori == false){
                    $resultat.= '<form action="" method="POST">
                            <input type="hidden" name="iduser" value="'.$Membre['id_membre'].'">
                            <input type="hidden" name="idannonce" value="'.$annonce['id_annonce'].'">
                            <button type="submit" class="favoris" name="addFavori"><i class="far fa-heart"></i></button>
                        </form>';   
                }else{
                    $resultat .= '<form action="" method="POST">
                            <input type="hidden" name="idSupr" value="'.$favori.'">
                            <button type="submit" class="favoris" name="removeFavori"><i class="fas fa-heart"></i></button>
                        </form>';
                }
            }
            $resultat .= '</div>';
    
            $resultat .= '<div class="annonce-details">';
                $resultat .= '<h4>'.$annonce['titre_annonce'].'</h4>';
                $resultat .= '<div class="description_annonce">';
                    $resultat .= '<p>'.substr($annonce['description_annonce'],0,255)."...".'</p>';
                $resultat .= '</div>';
                    $resultat .= ' <p><i class="fas fa-user"></i> Publié par : '.$annonce['prenom'].'</p>';
                    $resultat .= '<p><i class="fas fa-th-large"></i> : '.$annonce['titre_cat'].' / '. $annonce['titre_subcat'].'</p>';
                    $resultat .= ' <p><i class="fas fa-map-marker-alt"></i> : '.$annonce['name_country'].' / '. $annonce['name_region'].'</p>';
            $resultat .= '</div>';
            
            $resultat .= '<div class="annoncelink">';
                $resultat .= '<a href="annonce/'.$annonce['id_annonce'].'" class="annonce_btn">Voir l\'annonce</a>';
            $resultat .= '</div>';
    
        $resultat .= '</div>';
    $resultat .= '</div>';
    }
    $resultat .= '</div>';
    $resultat .= '</div>';
    $resultat .= '</div>';
}else{
    $resultat = '<div class="container">
                    <div class="nofind">
                        <div class="nofind_animation">
                            <lottie-player src="/assets/json/search.json"  background="transparent"  speed="1"  style="width: 200px; height: 200px;"  loop  autoplay></lottie-player>
                        </div>
                        <h3> Oups!, aucune annonces semble correspondre a vos critéres !</h3>
                    </div>
                </div>';
}



$tableau['resultat'] = $resultat;

echo json_encode($tableau);

