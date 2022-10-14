<?php
require_once __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../functions/post.php';

if (isset($_POST['id'])) {
    $req = $pdo->prepare("SELECT * FROM region WHERE country_id=" . $_POST['id']);
    $req->execute();
    $regions = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($regions);
}
