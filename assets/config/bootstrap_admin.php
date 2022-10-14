<?php

 require_once __DIR__ .'/bootstrap.php';

 
 if(role(ROLE_ADMIN)!==true){
  
  ajouterFlash('danger','Vous n\'avez pas les droits d\'acccès requis.');
  header('Location: ../login.php');
 }