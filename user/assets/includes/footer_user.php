
<?php
if(isset($_POST['news_submit_footer'])){

  if (!filter_var($_POST['email_footer'], FILTER_VALIDATE_EMAIL)) {
    ajouterFlash('danger','Email non valide.');
  }else{
    $ip = getIp();
    $req = $pdo->prepare(
      'INSERT INTO liste_newsletter(email, user_ip, date_enregistrement)
      VALUES (:email, :user_ip, :date)'
  );
  
  $req->bindParam(':email',$_POST['email_footer']);
  $req->bindParam(':user_ip',$ip);
  $req->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
  $req->execute();

  unset($_POST);
        ajouterFlash('success','Inscription validée');
  }
}
?>
</main>

<footer class="container-fluid">
  <div class="row">
    <div class="col-md-4 footer-part1">
      <h5>Newsletter</h5>
      <form method="post">
        <div>
          <input type="email" name="email_footer" class="input-field-footer" placeholder="Entrer votre email">
        </div>
        <button type="submit" class='submit-btn-footer' name="news_submit_footer"> S'inscrire</button>
      </form>
    </div>
    <div class="col-md-4 footer-part2">
      <h5>Informations</h5>
      <ul>
        <li><a href="../contactus">Contacter-nous</a></li>
        <li><a href="../cgu">CGU</a></li>
     </ul>
    </div>
    <div class="col-md-4 footer-part3">
      <h5>Suivez nous</h5>
      <div class="col-6 rsociaux facebook">
        <a href="https://www.facebook.com/Van-Dreams-110020710684274"><i class="fab fa-facebook-f"></i></a>     
      </div>
      <div class="col-6 rsociaux insta">
        <a href="https://www.instagram.com/vandreamsfr/"><i class="fab fa-instagram"></i></a>      
      </div>
      </div>
    </div>
  </div>

</footer>
<p class="copy">&copy Copyright 2020, Van Dreams Tous droits réservés</p>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"   integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="   crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/js/script_user.js"></script>
    <?php if(getMembre() !== null AND empty($_COOKIE["token"])) :?>
      <script type="text/javascript" src="assets/js/logout.js"></script>
    <?php endif;?>
  </body>
  </body>
</html>