<?php 
require_once __DIR__ . '/../config/bootstrap.php';

$email = $_POST['email_news'];
$ip = $_POST['ipUser'];

$resultat ='';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $resultat .='<div id="toats" class="notif alert-danger" onload="killToats()">';
        $resultat .= '<div class="toats_headers">';
            $resultat .='<a class="toats_die">';
                $resultat .='<i class="icon ion-md-close"></i>';
            $resultat .= '</a>';
            $resultat .='<h6><i class="fas fa-exclamation-circle"></i> Notification :</h6>';
        $resultat .='</div>';
        $resultat .='<div class="toats_core">';
            $resultat .='<p>adresse email non valide</p>';
        $resultat .='</div>';
    $resultat .='</div>';
    $resultat .='<script>
    setTimeout(function(){
        document.getElementById("toats").style.visibility= "hidden";
        }, 3000 );
    
    $(".toats_die").click(function(){
        $("#toats").css("visibility","hidden");
     });
    </script>';
    
    $tableau['resultat'] = $resultat;
    echo json_encode($tableau);
}else{
    $req = $pdo->prepare(
        'INSERT INTO liste_newsletter(email, user_ip, date_enregistrement)
        VALUES (:email, :user_ip, :date)'
    );
$req->bindParam(':email',$email);
$req->bindParam(':user_ip',$ip);
$req->bindValue(':date',(new DateTime())->format('Y-m-d H:i:s'));
$req->execute();

unset($email);
    
$resultat .='<div id="toats" class="notif alert-success" onload="killToats()">';
        $resultat .= '<div class="toats_headers">';
            $resultat .='<a class="toats_die">';
                $resultat .='<i class="icon ion-md-close"></i>';
            $resultat .= '</a>';
            $resultat .='<h6><i class="fas fa-exclamation-circle"></i> Notification :</h6>';
        $resultat .='</div>';
        $resultat .='<div class="toats_core">';
            $resultat .='<p>Inscription validée</p>';
        $resultat .='</div>';
    $resultat .='</div>';
    $resultat .='<script>
    setTimeout(function(){
        document.getElementById("toats").style.visibility= "hidden";
        }, 3000 );
    
    $(".toats_die").click(function(){
        $("#toats").css("visibility","hidden");
     });
    </script>';

    $tableau['resultat'] = $resultat;
    echo json_encode($tableau);
}



