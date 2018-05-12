<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$id = filter_input(INPUT_GET, 'user_id');
$type = filter_input(INPUT_GET, 'type');

// $idd = explode(',', $id);
// $idad = implode(',',$idd);

if($id and $type === 'artists'){
    // fetch table rows from mysql db
    $reponse = $bdd->query('SELECT * FROM paris WHERE user_id IN ('.$id.')');
    $data = $reponse->fetchAll();

    echo json_encode($data);
}
if($id and $type === 'videos'){
    // fetch table rows from mysql db
    $reponse = $bdd->query('SELECT * FROM paris WHERE user_id IN ('.$id.')');
    $data = $reponse->fetchAll();

    echo json_encode($data);
}
if($id and $type === 'tracks'){
    // fetch table rows from mysql db
    $reponse = $bdd->query('SELECT * FROM paris WHERE user_id IN ('.$id.')');
    $data = $reponse->fetchAll();

    echo json_encode($data);
}