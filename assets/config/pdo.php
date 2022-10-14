<?php

try{
$pdo = new PDO(
  sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME),
  DB_USER,
  DB_PASS,
  [
   PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING,
   PDO::MYSQL_ATTR_INIT_COMMAND 	=> 'SET NAMES utf8'
  ]
);
}catch(Exeption $e){
die('Erreur de connexion раMySQL: '.$e->getMessage());
}

// ini_set('display_errors', 1);
// error_reporting(E_ALL);