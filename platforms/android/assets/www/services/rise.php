<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$level = filter_input(INPUT_GET, 'level');
$value = filter_input(INPUT_GET, 'value');

if($level && $value){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT * FROM usa WHERE '.$level.' = :value ORDER BY points DESC LIMIT 15');
	$reponse->execute(['value' => $value]);
	$data = $reponse->fetchAll();


	echo json_encode($data);
}
