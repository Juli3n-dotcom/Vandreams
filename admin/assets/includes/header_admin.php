<?php
require_once __DIR__ . '/../../../assets/config/bootstrap_admin.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$page_title?> | Van Dreams </title>
    <link rel="icon" href="../assets/img/control-panel.png">
    <!--Ion Icons-->
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <!--Google Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Aldrich&display=swap" rel="stylesheet">
    <!--Our own stylesheet-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style_admin.css">
    
</head>
<body>
<header >
    <div class="container_header">
        <div class="nav-brand">
                <img src="../assets/img/control-panel.png" alt="">
                <a href="../index.php">Back-office</a>
        </div>
        
        
        
    
</div>
</header>

<div class="container-fluid">
    <div class="row">

    
<div class="nav col-md-2 d-none d-md-block  sidebar">
            <div class="menu">
                <a href="index_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </div>
            <div class="submenu" id="submenu">
                <a href="#"><i class="fas fa-signal"></i> Stats <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="views.php"> Visites</a>
                    <a href="#"> Statistiques</a>
                </div>
            </div>
            <div class="submenu" id="submenu">
                <a href="#"><i class="fas fa-user"></i> Utilisateurs <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="add_user.php"> Ajouter un utilisateur</a>
                    <a href="user.php"> Gestion des membres</a>
                </div>
            </div>
<?php
$annoncesSignale=$pdo->query('SELECT COUNT(*)AS nb FROM annonces WHERE est_signal = 1');
$data = $annoncesSignale ->fetch();
$signal = $data['nb'];
?>
            <div class="submenu" id="submenu">
<a href="#"><i class="fas fa-archive"></i> Les Annonces <span class="badge badge-secondary"><?= $signal ;?></span> <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="annonces.php"> Gestion des annonces</a>
                    <a href="annonces_signalee.php"> Annonces signalées <span class="badge badge-secondary"><?= $signal ;?></span></a>
                </div>
            </div>
<?php
$newmessages=$pdo->query('SELECT COUNT(*)AS nb FROM message_admin WHERE est_lu = 0');
$data = $newmessages ->fetch();
$newM = $data['nb'];
?>
            <div class="submenu" id="submenu">
                <a href="#"><i class="far fa-envelope"></i> Messages <span class="badge badge-secondary"><?= $newM ;?></span> <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="new_msg.php"> Nouveaux messages <span class="badge badge-secondary"><?= $newM ;?></span></a>
                    <a href="oldmsg.php"> Messages reçus</a>
                    <a href="sendmsg.php"> Messages envoyés</a>
                </div>
            </div>
            <div class="submenu" id="submenu">
                <a href="#"><i class="far fa-newspaper"></i> Newsletter <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="newsletter.php"> Liste de diffusion</a>
                    <a href="#"> Créer une Newsletter</a>
                </div>
            </div>
            <div class="submenu" id="submenu">
                <a href="#"><i class="fas fa-cubes"></i> Catégories <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="category.php"> Les catégories</a>
                    <a href="category.php#add_cat"> Ajouter une catégorie</a>
                    <a href="subcat.php"> Les sous catégories</a>
                    <a href="subcat.php#add_cat"> Ajouter une sous catégorie</a>
                </div>
            </div>
            <div class="submenu" id="submenu">
                <a href="#"><i class="fas fa-globe"></i> Pays & Regions <i class="fas fa-chevron-down right"></i></a>
                <div class="sub-content hide" id="sub-content">
                    <a href="country.php#country"> Les Pays</a>
                    <a href="country.php#add_country"> Ajouter un pays</a>
                    <a href="region.php"> Les Regions</a>
                    <a href="region.php#add_region"> Ajouter une region</a>
                </div>
            </div>
            <div class="menu">
                <a href="profil.php"><i class="fas fa-cogs"></i> Mon Profil</a>
            </div>
            <div class="menu">
                <a href="../logout.php"><i class="fas fa-power-off"></i> Se deconnecter</a>
            </div>
        
        </div>
        

<main class="ccol-md-9 ml-sm-auto col-lg-10 px-4">