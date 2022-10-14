<?php
require_once __DIR__ . '/assets/config/bootstrap.php';
require_once __DIR__ . '/assets/functions/post.php';

$Membre = getMembre($pdo, $_GET['id_membre'] ?? null);

if(($Membre === null)){
setcookie('post', true, time()+3600);
  ajouterFlash('danger','Veuillez vous connecter');
  header('location:login');
}


if(isset($_POST['add'])){

    if(empty($_POST['titre_annonce'])||strlen($_POST['titre_annonce'])>255){
        ajouterFlash('danger','Le titre doit contenir entre 1 et 255 caractéres.');
     
        }elseif (empty($_POST['description'])) {
        ajouterFlash('danger','Description manquante.');
     
         }elseif (empty($_POST['prix'])) {
        ajouterFlash('danger','prix manquant.');
     
        }elseif (!preg_match('~^[0-9-.]+$~',$_POST['prix'])) {
       ajouterFlash('danger','Merci d\'utiliser que des chiffres pour votre prix');

        }elseif (!preg_match('~^[0-9-.]+$~',$_POST['date'])) {
        ajouterFlash('danger','Merci de rentrer une date valide');
     
        }elseif (!preg_match('~^[0-9-.]+$~',$_POST['phone'])) {
        ajouterFlash('danger','saisir un numéro de téléphone valide');
     
        }elseif (!preg_match('~^[a-zA-Z0-9_-]+$~',$_POST['cp'])) {
       ajouterFlash('danger','saisir un code postal valide');

       //Intégration des photos
//Photo1
    }elseif ($_FILES['photo1']['error']!== UPLOAD_ERR_OK) {
        ajouterFlash('warning','[1] Probléme lors de l\'envoi du fichier.code '.$_FILES['photo1']['error']);
    
        }elseif ($_FILES['photo1']['size']<12 || exif_imagetype($_FILES['photo1']['tmp_name'])=== false ){
        ajouterFlash('danger','Le fichier 1 envoyé n\'est pas une image');
    //Photo2
        }elseif ($_FILES['photo2']['error']!== UPLOAD_ERR_OK) {
        ajouterFlash('warning','[2] Probléme lors de l\'envoi du fichier.code '.$_FILES['photo2']['error']);
    
        }elseif ($_FILES['photo2']['size']<12 || exif_imagetype($_FILES['photo2']['tmp_name'])=== false ){
        ajouterFlash('danger','Le fichier 2 envoyé n\'est pas une image');
    //Photo3
        }elseif ($_FILES['photo3']['error']!== UPLOAD_ERR_OK) {
        ajouterFlash('warning','[3] Probléme lors de l\'envoi du fichier.code '.$_FILES['photo3']['error']);
    
        }elseif ($_FILES['photo3']['size']<12 || exif_imagetype($_FILES['photo3']['tmp_name'])=== false ){
        ajouterFlash('danger','Le fichier 3 envoyé n\'est pas une image');
    
        }else{
        $extension1 = pathinfo($_FILES['photo1']['name'], PATHINFO_EXTENSION);
        $extension2 = pathinfo($_FILES['photo2']['name'], PATHINFO_EXTENSION);
        $extension3 = pathinfo($_FILES['photo3']['name'], PATHINFO_EXTENSION);
        $path1 = __DIR__.'/data';
        $path2 = __DIR__.'/data';
        $path3 = __DIR__.'/data';
        
    
        do{
             $filename1 = bin2hex(random_bytes(16));
             $complete_path1 = $path1.'/'.$filename1.'.'.$extension1;
        }while (file_exists( $complete_path1));
    
        do{
             $filename2 = bin2hex(random_bytes(16));
             $complete_path2 = $path2.'/'.$filename2.'.'.$extension2;
        }while (file_exists( $complete_path2));
    
         do{
             $filename3 = bin2hex(random_bytes(16));
             $complete_path3 = $path3.'/'.$filename3.'.'.$extension3;
        }while (file_exists( $complete_path3));

    
    
            if(!move_uploaded_file($_FILES['photo1']['tmp_name'],$complete_path1)){
            ajouterFlash('danger','La photo 1 n\'a pas pu être enregistrée');
    
            }elseif (!move_uploaded_file($_FILES['photo2']['tmp_name'],$complete_path2)) {
                ajouterFlash('danger','La photo 2 n\'a pas pu être enregistrée');
    
            }elseif (!move_uploaded_file($_FILES['photo3']['tmp_name'],$complete_path3)) {
                ajouterFlash('danger','La photo 3 n\'a pas pu être enregistrée');
            
       
            }else{

                if(file_exists($complete_path1)){

                    $source = $_FILES['photo1']['tmp_name'];
                    $path4 = __DIR__.'/data/thumb';
                    $filename4 = bin2hex(random_bytes(16));
                    $thumb = $filename4.'.'.$extension1;

                    redim($complete_path1, $path4.'/'.$thumb, '284', '213',50);
                }

    
                $req1 = $pdo->prepare(
                    'INSERT INTO photo(photo1, photo2, photo3, thumb)
                         VALUES (:photo1,:photo2,:photo3,:thumb)'
                          );
                            
                $req1->bindValue('photo1',$filename1.'.'.$extension1);
                $req1->bindValue('photo2',$filename2.'.'.$extension2);
                $req1->bindValue('photo3',$filename3.'.'.$extension3);
                $req1->bindValue('thumb',$thumb);
                $req1->execute();



                $id_photo = $pdo-> lastInsertId();
                $name = 'vd'.getMembre()['id_membre'].$_POST['category'].$_POST['subcat'].bin2hex(random_bytes(6));
           
                $req2 = $pdo->prepare(
                   'INSERT INTO annonces (titre_annonce, name, membre_id, description_annonce, prix, km, places, vasp, marque, modele, annee_modele, category_id, subcat_id, photo_id, country_id, region_id, cp, ville, telephone, est_publie, est_signal, date_enregistrement)
                    VALUES (:titre_annonce, :name, :membre_id, :description_annonce, :prix, :km, :places, :vasp, :marque, :modele, :annee_modele, :category_id, :subcat_id, :photo_id, :country_id, :region_id, :cp, :ville, :telephone, :publie, :signal, :date)'
                           );
                              $req2->bindParam(':titre_annonce',htmlspecialchars($_POST['titre_annonce']));
                              $req2->bindParam(':name',$name);
                              $req2->bindParam(':membre_id', getMembre()['id_membre'], PDO::PARAM_INT);
                              $req2->bindParam(':description_annonce',htmlspecialchars($_POST['description']));
                              $req2->bindParam(':prix',htmlspecialchars($_POST['prix']));
                              $req2->bindParam(':km',htmlspecialchars($_POST['km']));
                              $req2->bindParam(':places',htmlspecialchars($_POST['places']));
                              $req2->bindValue(':vasp',isset($_POST['vasp']),PDO::PARAM_BOOL);
                              $req2->bindParam(':marque',htmlspecialchars($_POST['marque']));
                              $req2->bindParam(':modele',htmlspecialchars($_POST['modele']));
                              $req2->bindParam(':annee_modele',htmlspecialchars($_POST['date']));
                              $req2->bindValue(':category_id', $_POST['category']);
                              $req2->bindValue(':subcat_id', $_POST['subcat']);
                              $req2->bindValue(':photo_id', $id_photo);
                              $req2->bindParam(':country_id',$_POST['pays']);
                              $req2->bindParam(':region_id',$_POST['region']);
                              $req2->bindParam(':cp',htmlspecialchars($_POST['cp']));
                              $req2->bindParam(':ville',htmlspecialchars($_POST['ville']));
                              $req2->bindParam(':telephone',htmlspecialchars($_POST['phone']));
                              $req2->bindValue(':publie',isset($_POST['est_publie']),PDO::PARAM_BOOL);
                              $req2->bindValue(':signal',0);
                              $req2->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
                              $req2->execute();
           }
        $annonce = $pdo-> lastInsertId();
        $email = getMembre()['email'];
        $prenom = getMembre()['prenom'];
        $title = $_POST['titre_annonce'];


        $header="MIME-Version: 1.0\r\n";
        $header.='From:"vandreams.fr"<postmaster@vandreams.fr>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message = '
                <html>
                <head>
                  <title>Votre annonce - Van Dreams.fr</title>
                  <meta charset="utf-8" />
                </head>
                <body>
                  <font color="#303030";>
                    <div align="center">
                      <table width="600px">
                        <tr>
                       <img src="https://www.vandreams.fr/assets/img/logo3.png" alt="logo" width="200" style="display: block;margin-left: auto;
                         margin-right: auto;">
                          <td style="background-color: #EEE;height: 600px; border-radius: 10%; font-size: 20px; text-align:center;>
                            <div align="center">Bonjour <b>'.$prenom.'</b>,</div>
                            <br><br>
                            <div align="center">Félicitation votre annonce : <b>'.$title.'</b> est en ligne.</div>
                            <br><br>
                            <div align="center"><a href=https://vandreams.fr/annonce/?id='.$annonce.' style=" width: 30%;
                                padding: 10px 30px;
                                cursor: pointer;
                                display: block;
                                margin: auto;
                                color: #FFF;
                                background: linear-gradient(to right, #00bd71,#008656);
                                border: 0;
                                outline: none;
                                border-radius: 30px;
                                text-decoration: none;"> Voir mon annonce </a></div>
                            <br><br>
                            <div align="center">A bientôt sur <a href="vandreams.fr">VanDreams.fr</a> !</div>
                            
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <font size="2">
                              Ceci est un email automatique, merci de ne pas y répondre
                            </font>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </font>
                </body>
                </html>
                ';
        mail($email, "Votre annonce - vandreams.fr", $message, $header);

        unset($_POST);
        session_write_close();
        header('location:annonce/'.$annonce);
        ajouterFlash('success','Annonce Validée');
   } 

}





$page_title ='Dépot d\'annonces';
include __DIR__.'/assets/includes/header.php';
?>

<?php include __DIR__.'/assets/includes/flash.php';?>

<div class="hero_depot">
    <div class="form-box_depot">
        <div class="button-box_depot">
            <div id="btn_depot"></div>
            <button type="button" class="toggle-btn"  id="infos_btn">1</button>
            <button type="button" class="toggle-btn"  id="infos2_btn">2</button>
            <button type="button" class="toggle-btn"  id="photo_btn">3</button>
            <button type="button" class="toggle-btn"  id="adress_btn">4</button>
            <button type="button" class="toggle-btn"  id="valid_btn">5</button>
        </div>
        <form action="post.php" method="post" enctype="multipart/form-data">
            <div class="input-group_depot" id="depot_1">
                <h3 class="title_part">Les détails de votre annonces</h3>
                    <input type="text" class="input-field" name="titre_annonce" placeholder="Le titre de votre annonce" value="<?= htmlspecialchars($_POST['titre_annonce']??'');?>">
                    <textarea class="input-field" name="description" cols="30" rows="10" placeholder="Description de votre annonce" 
                    ><?= htmlspecialchars($_POST['description']??'');?></textarea>
                    <input type="number"  class="input-field" name="prix" placeholder="Votre Prix en €" value="<?= htmlspecialchars($_POST['prix']??'');?>">
                    <button type="button" class="submit-btn_depot" id="next1">Suivant</button>
            </div>
        
            <div class="input-group_depot" id="depot_2">
                <h3 class="title_part">Le véhicule</h3>
                <div class="depot_radio">
                    <h5  class="label_name">Catégories :</h5> 
                    <?php foreach(getCategory($pdo) as $cat) : ?>
                        <input type="radio" name="category" value="<?=$cat['id_category'];?>">
                        <label for="category"><?=$cat['titre_cat'];?></label>
                    <?php endforeach; ?> 
                </div>
                <div class="depot_radio">
                    <h5  class="label_name">Les Sous Catégories :</h5> 
                    <?php foreach(getSubCategory($pdo) as $subcat) : ?>
                        <input type="radio" name="subcat" value="<?=$subcat['id_sub_cat'];?>">
                        <label for="subcat"><?=$subcat['titre_subcat'];?></label>
                    <?php endforeach; ?> 
                </div>
                <span id="vasp">VASP</span><input type="checkbox" class="vasp" name="vasp">
                <input type="text" name="marque" class="input-field" placeholder="La marque du véhicule" value="<?= htmlspecialchars($_POST['marque']??'');?>">
                <input type="text" name="modele" class="input-field" placeholder="Le modele" value="<?= htmlspecialchars($_POST['modele']??'');?>">
                <input type="number"  name="km" class="input-field" placeholder="Nombre de Kilométre" value="<?= htmlspecialchars($_POST['km']??'');?>">
                <input type="number"   name="date" class="input-field" placeholder="Date de mise en circulation" value="<?= htmlspecialchars($_POST['date']??'');?>">
                <input type="number"   name="places" class="input-field" placeholder="Nombre de sièges" value="<?= htmlspecialchars($_POST['places']??'');?>">
                <div class="btn_bottom">
                    <button type="button" class="prev-btn" id="prev1">Précedent</button>
                    <button type="button" class="submit-btn_depot" id="next2">Suivant</button>
                </div>
            </div>

            <div class="input-group_depot" id="depot_3">
                <h3 class="title_part">Les Photos</h3>
                        <p>Pour une bonne visibilité de votre annonce, nous vous recommandons de privilégier les photos en format paysage</p>
                <div class="picture_box">
                    <input type="file" class="display_none" id="photo1" name="photo1">
                    <label id="etiquette_image1" class="depot_annonce_photo" for="photo1"><i class="far fa-image"></i></label>
                    <input type="file" class="display_none" id="photo2" name="photo2">
                    <label id="etiquette_image2" class="depot_annonce_photo" for="photo2"><i class="far fa-image"></i></label>
                    <input type="file" class="display_none" id="photo3" name="photo3">
                    <label id="etiquette_image3" class="depot_annonce_photo" for="photo3"><i class="far fa-image"></i></label>
                </div>
                <div class="container-fluid">
                    <div class="row">       
                        <?php for($i =0; $i <3; $i++) : ?>
                            <div class=""id="preview<?= ($i+1)?>"></div>
                        <?php endfor; ?>                      
                    </div>
                </div>      
                <div class="btn_bottom">
                    <button type="button" class="prev-btn" id="prev2">Précedent</button>
                    <button type="button" class="submit-btn_depot" id="next3">Suivant</button>
                </div>
            </div>

            <div class="input-group_depot" id="depot_4">
                <h3 class="title_part">Coordonnées</h3>
                <div class="depot_select">
                    
                        <label class="depot_label1" for="pays">Pays : </label>
                        <select name="pays" class="custom-dropdown" id="country">
                            <option selected>Choisir...</option>
                            <?php foreach(getCountry($pdo) as $country) : ?>
                            <option value="<?=$country['id_country'];?>"><?=$country['name_country'];?></option>
                            <?php endforeach; ?>
                            </select>
                    
                </div>
                <br>
                <div class="depot_select">
                    <label for="pays">Région : </label>
                        <select name="region" id="regions" class="custom-dropdown">
                            <option selected>Choisir un pays en premier</option>
                        </select>
                </div>
                <input type="text" name="ville" class="input-field" placeholder="La ville" value="<?= htmlspecialchars($_POST['ville']??'');?>">
                <input type="text"  name="cp" class="input-field" placeholder="Le code postal" value="<?= htmlspecialchars($_POST['cp']??'');?>">
                <input type="tel"  name="phone" class="input-field" placeholder="Votre numéro de téléphone" value="<?= htmlspecialchars($_POST['phone']??'');?>"> 
                <input type="checkbox" class="check-box" name="est_publie"><span>masquer mon numéro</span>
                <div class="btn_bottom">
                    <button type="button" class="prev-btn" id="prev3">Précedent</button>
                    <button type="button" class="submit-btn_depot" id="next4">Suivant</button>
                </div>
            </div>
            <div class="input-group_depot" id="depot_5">
            <h3 class="title_part">Validation</h3>
            <input type="checkbox" class="check-box" name="payment"><span>Je publie gratuitement mon annonce</span>
            <input type="submit" class="submit-btn_depot" name="add" value="Valider">
            </div>
        </form>
    </div>
</div>

<script>
  document.querySelector("input[type=number]")
  .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))
</script>

<script type="text/javascript" src="assets/js/depot.js"></script>
<?php
include __DIR__.'/assets/includes/cookie.php';
include __DIR__.'/assets/includes/footer.php';
?>