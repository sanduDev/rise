<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$id = filter_input(INPUT_GET, 'user_id');
if($id){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT * FROM paris WHERE user_id = :user_id');
	$reponse->execute(['user_id' => $id]);
	$data = $reponse->fetchAll();

	echo json_encode($data);
}