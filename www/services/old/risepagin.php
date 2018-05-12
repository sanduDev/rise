<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$level = filter_input(INPUT_GET, 'level');
$value = filter_input(INPUT_GET, 'value');

$page = filter_input(INPUT_GET, 'p');

$limit = 15;
$new = ($page - 1) * $limit;

if($level && $value){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT * FROM paris WHERE '.$level.' = :value  ORDER BY points DESC LIMIT '.$new.', '.$limit);
	$reponse->execute([
		'value' => $value
	]);
	$data = $reponse->fetchAll();


	echo json_encode($data);
}
