<?php

// vérification si utilisateur est connecté
function getMembre() :?array
{
 return $_SESSION['membre'] ?? null;
}

//vérification du ROLE de l'utilisateur
 function role(int $role): bool
 {
  
    if (getMembre() === null){
      
      return false;
    } 
  
      return getMembre()['statut'] == $role;
         
 }