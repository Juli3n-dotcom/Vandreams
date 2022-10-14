<?php
setcookie('token','',time()-3600);
setcookie('post','',time()-3600);
require_once __DIR__ . '/assets/config/bootstrap.php';
unset($_SESSION['membre']);
ajouterFlash('success','Vous avez été déconnecté');
header('Location: welcome');

