<?php
if(isset($_COOKIE['accept_cookie'])){
    $showcookie = false;
}else{
    $showcookie = true;
}
?>

<?php if($showcookie):?>
<div class="cookie-alert" id="cookie">
    <div class="cookietxt">
        <p>"Nous utilisons les cookies pour vous fournir des fonctionnalités afin d’améliorer l’expérience de nos utilisateurs. 
        Les cookies sont des données qui sont téléchargés ou stockés sur votre ordinateur ou sur tout autre appareil.</p>
        <p> En cliquant sur ”J’accepte”, vous acceptez l’utilisation des cookies. Vous pourrez toujours les désactiver ultérieurement.</p>
    </div>
    <a href="assets/functions/accept_cookie.php">J'accepte</a>
</div>
<?php endif;?>