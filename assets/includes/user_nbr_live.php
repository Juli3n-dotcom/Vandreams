<?php

require_once __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../functions/register.php';

$temps_session = 15;
$temps_actuel = date("U");
$user_ip = getIp();
$req_ip_exist = $pdo->prepare('SELECT * FROM online WHERE user_ip = ?');
$req_ip_exist->execute(array($user_ip));
$ip_existe = $req_ip_exist->rowCount();
if($ip_existe == 0) {
   $add_ip = $pdo->prepare('INSERT INTO online(user_ip,time) VALUES(?,?)');
   $add_ip->execute(array($user_ip,$temps_actuel));
} else {
   $update_ip = $pdo->prepare('UPDATE online SET time = ? WHERE user_ip = ?');
   $update_ip->execute(array($temps_actuel,$user_ip));
}
$session_delete_time = $temps_actuel - $temps_session;
$del_ip = $pdo->prepare('DELETE FROM online WHERE time < ?');
$del_ip->execute(array($session_delete_time));
$show_user_nbr = $pdo->query('SELECT * FROM online');
$user_nbr = $show_user_nbr->rowCount();
?>